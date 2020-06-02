<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the
 * Gutenberg block.
 *
 * @package mutualaidnyc
 */

namespace MutualAidNYC\Blocks;

const BLOCKS = [ 'resources' ];
const ADD_BLOCK_HOOK = 'mutualaidnyc_add_block';

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * @see https://wordpress.org/gutenberg/handbook/designers-developers/developers/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function block_init() {
	// Skip block registration if Gutenberg is not enabled/merged.
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	$script_asset_path = MANY_ASSETS_PATH . '/blocks/index.asset.php';
	if ( ! file_exists( $script_asset_path ) ) {
		return;
	}
	$script_asset = require MANY_ASSETS_PATH . '/blocks/index.asset.php';
	$index_js     = MANY_ASSETS_URL . '/blocks/index.js';
	$block_paths  = MANY_ROOT_PATH . '/blocks';

	wp_register_script(
		'theme-blocks-editor',
		$index_js,
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	foreach ( BLOCKS as $block ) {
		if ( file_exists( "$block_paths/$block/block.php" ) ) {
			require_once "$block_paths/$block/block.php";
		}
	}
	do_action( ADD_BLOCK_HOOK );
}
