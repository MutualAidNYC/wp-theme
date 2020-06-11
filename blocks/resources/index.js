import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';

// Commented out due to bug: https://github.com/WordPress/gutenberg/issues/22776
// import './style.css';

const ResourcesEdit = ( { attributes: { className } } ) => {
	return (
		<div className={ className }>
			<ServerSideRender block="mutualaidnyc/resources" />
		</div>
	);
};

registerBlockType( 'mutualaidnyc/resources', {
	title: 'Resources',
	icon: 'list-view',
	category: 'widgets',
	edit: ResourcesEdit,
} );