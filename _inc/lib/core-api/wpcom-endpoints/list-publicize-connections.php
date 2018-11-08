<?php
/**
 * Publicize: get connection list data for current user.
 */
class WPCOM_REST_API_V2_Endpoint_List_Publicize_Connections extends WP_REST_Controller {
	public function __construct() {
		$this->namespace = 'wpcom/v2';
		$this->rest_base = 'publicize/connections';
	}

	public function register_routes() {
		register_rest_route( $this->namespace, '/' . $this->rest_base, array(
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permission_check' ),
			),
			'schema' => array( $this, 'get_public_item_schema' ),
		) );
	}

	protected function get_connection_schema_properties() {
		return array(
			'id' => array(
				'description' => __( 'Unique identifier for the Publicize Connection', 'jetpack' ),
				'type' => 'string',
			),
			'service_name' => array(
				'description' => __( 'Alphanumeric identifier for the Publicize Service', 'jetpack' ),
				'type' => 'string',
			),
			'display_name' => array(
				'description' => __( 'Username of the connected account', 'jetpack' ),
				'type' => 'string',
			),
			'global' => array(
				'description' => __( 'Is this connection available to all users?', 'jetpack' ),
				'type' => 'boolean',
			),
		);
	}

	public function get_item_schema() {
		$schema = array(
			'$schema' => 'http://json-schema.org/draft-04/schema#',
			'title' => 'jetpack-publicize-connection',
			'type' => 'object',
			'properties' => $this->get_connection_schema_properties(),
		);

		return $this->add_additional_fields_schema( $schema );
	}

	protected function get_connections() {
		global $publicize;

		$items = array();

		foreach ( (array) $publicize->get_services( 'connected' ) as $service_name => $connections ) {
			foreach ( $connections as $connection ) {
				$connection_meta = $publicize->get_connection_meta( $connection );
				$connection_data = $connection_meta['connection_data'];

				$items[] = array(
					'id' => $publicize->get_connection_unique_id( $connection ),
					'service_name' => $service_name,
					'display_name' => $publicize->get_display_name( $service_name, $connection ),
					'global' => 0 == $connection_data['user_id'],
				);
			}
		}

		return $items;
	}

	public function get_items( $request ) {
		$items = array();

		foreach ( $this->get_connections() as $item ) {
			$items[] = $this->prepare_item_for_response( $item, $request );
		}

		$response = rest_ensure_response( $items );
                $response->header( 'X-WP-Total', count( $items ) );
                $response->header( 'X-WP-TotalPages', 1 );

		return $response;
	}

	function prepare_item_for_response( $connection, $request ) {
		$fields = $this->get_fields_for_response( $request );

		$response_data = array();
		foreach ( $connection as $field => $value ) {
			if ( in_array( $field, $fields, true ) ) {
				$response_data[$field] = $value;
			}
		}

		return $response_data;
	}

	/**
	 * Verify that user can publish posts.
	 *
	 * @since 6.7.0
	 *
	 * @return bool Whether user has the capability 'publish_posts'.
	 */
	public function get_items_permission_check() {
		if ( current_user_can( 'publish_posts' ) ) {
			return true;
		}

		return new WP_Error(
			'invalid_user_permission_publicize',
			Jetpack_Core_Json_Api_Endpoints::$user_permissions_error_msg,
			array( 'status' => Jetpack_Core_Json_Api_Endpoints::rest_authorization_required_code() )
		);
	}
}

wpcom_rest_api_v2_load_plugin( 'WPCOM_REST_API_V2_Endpoint_List_Publicize_Connections' );