/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { CheckboxControl } from '@wordpress/components';
import { compose } from '@wordpress/compose';
import { withDispatch, withSelect } from '@wordpress/data';

const LikesCheckbox = ( { areLikesEnabled, editPost } ) => (
	<CheckboxControl
		label={ __( 'Show likes.', 'jetpack' ) }
		checked={ areLikesEnabled }
		onChange={ value => {
			editPost( { jetpack_likes_enabled: value } );
		} }
	/>
);

// Fetch the post meta.
const applyWithSelect = withSelect( select => {
	const { getEditedPostAttribute } = select( 'core/editor' );
	const areLikesEnabled = getEditedPostAttribute( 'jetpack_likes_enabled' );

	return { areLikesEnabled };
} );

// Provide method to update post meta.
const applyWithDispatch = withDispatch( dispatch => {
	const { editPost } = dispatch( 'core/editor' );

	return { editPost };
} );

// Combine the higher-order components.
export default compose( [ applyWithSelect, applyWithDispatch ] )( LikesCheckbox );
