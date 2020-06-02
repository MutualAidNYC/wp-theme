import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';

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
