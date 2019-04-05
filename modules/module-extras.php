<?php
/*
 * Load module code that is needed even when a module isn't active.
 * For example, if a module shouldn't be activatable unless certain conditions are met, the code belongs in this file.
 */

// Include extra tools that aren't modules, in a filterable way
$tools = array(
	'theme-tools/social-links.php',
	'theme-tools/random-redirect.php',
	'theme-tools/featured-content.php',
	'theme-tools/infinite-scroll.php',
	'theme-tools/responsive-videos.php',
	'theme-tools/site-logo.php',
	'theme-tools/site-breadcrumbs.php',
	'theme-tools/social-menu.php',
	'theme-tools/content-options.php',
	'custom-post-types/comics.php',
	'custom-post-types/testimonial.php',
	'custom-post-types/nova.php',
	'theme-tools.php',
	'seo-tools/jetpack-seo-utils.php',
	'seo-tools/jetpack-seo-titles.php',
	'seo-tools/jetpack-seo-posts.php',
	'simple-payments/simple-payments.php',
	'verification-tools/verification-tools-utils.php',
	'woocommerce-analytics/wp-woocommerce-analytics.php',
	'geo-location.php',
	'calypsoify/class.jetpack-calypsoify.php',

	// Keep working the VideoPress videos in existing posts/pages when the module is deactivated
	'videopress/utility-functions.php',
	'videopress/class.videopress-gutenberg.php',

	'plugin-search.php',
);

// Not every tool needs to be included if Jetpack is inactive and not in development mode
if ( ! Jetpack::is_active() && ! Jetpack::is_development_mode() ) {
	$tools = array(
		'seo-tools/jetpack-seo-utils.php',
		'seo-tools/jetpack-seo-titles.php',
		'seo-tools/jetpack-seo-posts.php',
	);
}

/**
 * Filter extra tools (not modules) to include.
 *
 * @since 2.4.0
 * @since 5.4.0 can be used in multisite when Jetpack is not connected to WordPress.com and not in development mode.
 *
 * @param array $tools Array of extra tools to include.
 */
$jetpack_tools_to_include = apply_filters( 'jetpack_tools_to_include', $tools );

if ( ! empty( $jetpack_tools_to_include ) ) {
	foreach ( $jetpack_tools_to_include as $tool ) {
		if ( file_exists( JETPACK__PLUGIN_DIR . '/modules/' . $tool ) ) {
			require_once JETPACK__PLUGIN_DIR . '/modules/' . $tool;
		}
	}
}

/**
 * Add the "(Jetpack)" suffix to the widget names
 */
function jetpack_widgets_add_suffix( $widget_name ) {
	return sprintf( __( '%s (Jetpack)', 'jetpack' ), $widget_name );
}
add_filter( 'jetpack_widget_name', 'jetpack_widgets_add_suffix' );

add_action( 'blog_privacy_selector', 'priv_notice_privacy_selector' );

/**
 * Echos notice directing site owners to Jetpack's Private Site feature.
 */
function priv_notice_privacy_selector() {
	?>
	<p>
		<?php
			printf(
				'%s <a href="%s#/security?term=private">%s</a>',
				esc_html__( 'You can also make your site completely private by allowing only registered users to see your site.', 'jetpack' ),
				esc_url( admin_url( 'admin.php?page=jetpack' ),
				esc_html__( 'Go to Private Sites settings', 'jetpack' )
			);
		?>
	</p>

	<?php
}

