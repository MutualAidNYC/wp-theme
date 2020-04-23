<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package many-theme
 */

namespace MutualAidNYC;

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @throws Error When file is missing.
 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$script_asset_path = MANY_ASSETS_PATH . '/blocks/index.asset.php';
	if ( ! file_exists( $script_asset_path ) ) {
		// phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_trigger_error
		trigger_error(
			'You need to run `npm start` or `npm run build` for the "create-block/media-border" block first.',
			E_USER_WARNING
		);
		return;
	}
	$script_asset = require MANY_ASSETS_PATH . '/blocks/index.asset.php';
	$index_js     = MANY_ASSETS_URL . '/blocks/index.js';
	wp_register_script(
		'media-border-block-editor',
		$index_js,
		$script_asset['dependencies'],
		$script_asset['version'],
		false
	);

	$block_paths = MANY_ROOT_PATH . '/blocks';
	$editor_css  = 'media-border/editor.css';
	wp_register_style(
		'media-border-block-editor',
		MANY_ROOT_URL . "/blocks/$editor_css",
		array(),
		filemtime( "$block_paths/$editor_css" )
	);

	$style_css = 'media-border/style.css';
	wp_register_style(
		'media-border-block',
		MANY_ROOT_URL . "/blocks/$style_css",
		array(),
		filemtime( "$block_paths/$style_css" )
	);

	register_block_type(
		'many-theme/media-border',
		array(
			'editor_script' => 'media-border-block-editor',
			'editor_style'  => 'media-border-block-editor',
			'style'         => 'media-border-block',
		)
	);
}
