import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

import edit from './edit';
import save from './save';

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType( 'mutualaidnyc/media-border', {
	title: __( 'Media Border', 'mutualaidnyc' ),
	description: __(
		'Displays a box with optional media and text inside a border color.',
		'mutualaidnyc'
	),
	category: 'layout',
	icon: 'excerpt-view',
	keywords: [ __( 'image' ), __( 'photo' ), __( 'pics' ), __( 'border' ) ],
	attributes: {
		align: {
			type: 'string',
			default: 'left',
		},
		content: {
			type: 'array',
			source: 'children',
			selector: 'p',
		},
		color: {
			type: 'string',
		},
		customColor: {
			type: 'string',
		},
		mediaAlt: {
			type: 'string',
			source: 'attribute',
			selector: 'figure img',
			attribute: 'alt',
			default: '',
		},
		mediaPosition: {
			type: 'string',
			default: 'left',
		},
		mediaId: {
			type: 'number',
		},
		mediaUrl: {
			type: 'string',
			source: 'attribute',
			selector: 'figure img',
			attribute: 'src',
		},
		focalPoint: {
			type: 'object',
		},
	},
	supports: {
		html: false,
	},

	edit,
	save,
} );
