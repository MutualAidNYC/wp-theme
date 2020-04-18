<?php
/**
 * Main functions.
 *
 * @package mutualaidnyc
 */

namespace MutualAidNYC;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup' );

/**
 * Initialization of theme.
 *
 * @return void
 */
function setup() : void {
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles' );
}

/**
 * Enqueues the parent theme styles.
 *
 * @return void
 */
function enqueue_styles() : void {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
