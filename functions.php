<?php
/**
 * Main functions.
 *
 * @package mutualaidnyc
 */

namespace MutualAidNYC;

add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup', 11 );

/**
 * Initialization of theme.
 *
 * @return void
 */
function setup() : void {
	remove_action( 'wp_enqueue_scripts', 'twentytwenty_register_styles' );
	remove_action( 'enqueue_block_editor_assets', 'twentytwenty_block_editor_styles', 1 );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles', 10 );

	add_filter( 'twentytwenty_get_elements_array', '__return_empty_array' );

	$editor_color_palette = [
		[
			'slug'  => 'primary',
			'name'  => __( 'Primary', 'mutualaidnyc' ),
			'color' => '#000000',
		],
		[
			'slug'  => 'secondary',
			'name'  => __( 'Secondary', 'mutualaidnyc' ),
			'color' => '#c24532',
		],
		[
			'slug'  => 'dark',
			'name'  => __( 'Dark Accent', 'mutualaidnyc' ),
			'color' => '#47133d',
		],
		[
			'slug'  => 'dark-alt',
			'name'  => __( 'Alternative Dark Accent', 'mutualaidnyc' ),
			'color' => '#204045',
		],
		[
			'slug'  => 'accent-background',
			'name'  => __( 'Accent Background', 'mutualaidnyc' ),
			'color' => '#f7cf56',
		],
		[
			'slug'  => 'light-background',
			'name'  => __( 'Light Background', 'mutualaidnyc' ),
			'color' => '#a4cacb',
		],
	];
	add_theme_support( 'editor-color-palette', $editor_color_palette );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Enqueue editor styles.
	add_editor_style(
		array(
			mutualaidnyc_fonts_url(),
			'assets/variables.css',
			'style-editor.css',
		)
	);
}

/**
 * Enqueues the theme styles.
 *
 * @return void
 */
function enqueue_styles() : void {

	$theme_version  = wp_get_theme()->get( 'Version' );
	$parent_version = wp_get_theme( 'twentytwenty' )->get( 'Version' );

	// Enqueue fonts.
	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_style( 'mutualaidnyc-fonts', mutualaidnyc_fonts_url(), array(), null );

	// Theme variables.
	wp_enqueue_style( 'mutualaid-variables-style', get_stylesheet_directory_uri() . '/assets/variables.css', array(), wp_get_theme()->get( 'Version' ) );

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', [], $parent_version );

	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), [ 'parent-style' ], $theme_version );
}

/**
 * Add Google webfonts
 *
 * @return string
 */
function mutualaidnyc_fonts_url() : string {
	$fonts_url = '';

	$font_families   = array();
	$font_families[] = 'family=Poppins:wght@700;900';
	$font_families[] = 'family=Francois+One';
	$font_families[] = 'family=Karla:ital,wght@0,400;0,700;1,400;1,700';
	$font_families[] = 'display=swap';

	// Make a single request for the theme fonts.
	$fonts_url = 'https://fonts.googleapis.com/css2?' . implode( '&', $font_families );

	return $fonts_url;
}
