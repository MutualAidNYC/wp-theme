<?php
/**
 * Main functions.
 *
 * @package mutualaidnyc
 */

namespace MutualAidNYC;

use WP_Customize_Manager;

define( 'MANY_ROOT_PATH', get_stylesheet_directory() );
define( 'MANY_ROOT_URL', get_stylesheet_directory_uri() );
define( 'MANY_ASSETS_PATH', MANY_ROOT_PATH . '/assets' );
define( 'MANY_ASSETS_URL', MANY_ROOT_URL . '/assets' );

require_once MANY_ROOT_PATH . '/blocks/blocks.php';

add_action( 'after_setup_theme', __NAMESPACE__ . '\\setup', 11 );

/**
 * Initialization of theme.
 *
 * @return void
 */
function setup() : void {
	remove_action( 'wp_enqueue_scripts', 'twentytwenty_register_styles' );
	remove_action( 'enqueue_block_editor_assets', 'twentytwenty_block_editor_styles', 1 );
	remove_action( 'widgets_init', 'twentytwenty_sidebar_registration' );
	add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\enqueue_block_styles' );
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles' );
	add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_editor_styles' );
	add_action( 'init', __NAMESPACE__ . '\\block_init' );

	add_filter( 'twentytwenty_get_elements_array', '__return_empty_array' );
	add_filter( 'theme_mod_custom_logo', '__return_true' );
	add_filter( 'get_custom_logo', __NAMESPACE__ . '\\filter_logo' );
	add_action( 'customize_controls_enqueue_scripts', __NAMESPACE__ . '\\remove_customizer_scripts', 11 );
	add_action( 'customize_register', __NAMESPACE__ . '\\register_customizer', 11 );

	add_filter( 'trp_skip_gettext_processing', __NAMESPACE__ . '\\skip_jetpack_translation', 10, 4 );

	global $content_width;
	$content_width = 700;

	$editor_color_palette = [
		[
			'slug'  => 'primary',
			'name'  => __( 'Primary', 'mutualaidnyc' ),
			'color' => '#000000',
		],
		[
			'slug'  => 'accent',
			'name'  => __( 'Accent', 'mutualaidnyc' ),
			'color' => '#c24532',
		],
		[
			'slug'  => 'secondary',
			'name'  => __( 'Secondary', 'mutualaidnyc' ),
			'color' => '#204045',
		],
		[
			'slug'  => 'tertiary',
			'name'  => __( 'Tertiary', 'mutualaidnyc' ),
			'color' => '#47133d',
		],
		[
			'slug'  => 'highlight',
			'name'  => __( 'Highlight Background', 'mutualaidnyc' ),
			'color' => '#f7cf56',
		],
		[
			'slug'  => 'highlight-alt',
			'name'  => __( 'Alternative Highlight Background', 'mutualaidnyc' ),
			'color' => '#a4cacb',
		],
	];
	add_theme_support( 'editor-color-palette', $editor_color_palette );

	// Add support for editor styles.
	add_theme_support( 'editor-styles' );

	// Enqueue editor styles.
	add_editor_style(
		array(
			fonts_url(),
			'assets/variables.css',
			'assets/colors.css',
			'style-editor.css',
		)
	);

	// Disables custom font sizes and colors.
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'disable-custom-colors' );

	// Remove theme support for items that are hard-coded.
	remove_theme_support( 'custom-background' );
	remove_theme_support( 'custom-logo' );

	// Override parent theme settings.
	$theme_mods = [
		'enable_header_search'                    => false,
		'cover_template_overlay_background_color' => '#000000',
		'cover_template_overlay_opacity'          => 30,
	];
	foreach ( $theme_mods as $key => $value ) {
		add_filter(
			"theme_mod_{$key}",
			function() use ( $value ) {
				return $value;
			}
		);
	}
}

/**
 * Enqueues block styles for both the front-end and editor.
 *
 * @return void
 */
function enqueue_block_styles() : void {
	$theme_version = wp_get_theme()->get( 'Version' );
	wp_register_style( 'theme-style-variables', get_stylesheet_directory_uri() . '/assets/variables.css', [], $theme_version );
	wp_register_style( 'theme-style-colors', get_stylesheet_directory_uri() . '/assets/colors.css', [], $theme_version );
}

/**
 * Enqueues the theme styles.
 *
 * @return void
 */
function enqueue_styles() : void {

	$theme_version  = wp_get_theme()->get( 'Version' );
	$parent_version = wp_get_theme( 'twentytwenty' )->get( 'Version' );

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', [], $parent_version );

	// Enqueue fonts.
	// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	wp_enqueue_style( 'theme-fonts', fonts_url(), [], null );

	wp_enqueue_style( 'theme-style', get_stylesheet_uri(), [ 'parent-style', 'theme-style-variables', 'theme-style-colors' ], $theme_version );
}

/**
 * Enqueues styles for the block editor.
 *
 * @since 1.0.1
 * @return void
 */
function enqueue_editor_styles() : void {
	wp_enqueue_style( 'theme-editor-tweaks', get_stylesheet_directory_uri() . '/assets/editor-tweaks.css', [ 'theme-style-variables' ], wp_get_theme()->get( 'Version' ) );
}

/**
 * Register block style variations.
 */
if ( function_exists( 'register_block_style' ) ) {
	register_block_style(
		'core/media-text',
		array(
			'name'         => 'border-alt',
			'label'        => __( 'Secondary', 'mutualaidnyc' ),
			'style_handle' => 'theme-style',
		)
	);

	register_block_style(
		'core/media-text',
		array(
			'name'         => 'border-dark',
			'label'        => __( 'Tertiary', 'mutualaidnyc' ),
			'style_handle' => 'theme-style',
		)
	);

	register_block_style(
		'core/media-text',
		array(
			'name'         => 'border-accent',
			'label'        => __( 'Accent', 'mutualaidnyc' ),
			'style_handle' => 'theme-style',
		)
	);

	register_block_style(
		'core/paragraph',
		array(
			'name'         => 'emphasis',
			'label'        => __( 'Emphasized', 'mutualaidnyc' ),
			'style_handle' => 'theme-style',
		)
	);
}

/**
 * Add Google webfonts
 *
 * @return string
 */
function fonts_url() : string {
	$fonts_url = '';

	$font_families   = array();
	$font_families[] = 'family=Poppins:wght@700;900';
	$font_families[] = 'family=Karla:ital,wght@0,400;0,700;1,400;1,700';
	$font_families[] = 'display=swap';

	// Make a single request for the theme fonts.
	$fonts_url = 'https://fonts.googleapis.com/css2?' . implode( '&', $font_families );

	return $fonts_url;
}

/**
 * Filters the logo image.
 *
 * @param string $html The original HTML.
 * @return string      HTML with SVG.
 */
function filter_logo( string $html ) : string {
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$svg_markup = file_get_contents( __DIR__ . '/assets/logo.svg' );
	return str_replace( '></', ">$svg_markup</", $html );
}

/**
 * Dequeues parent theme Customizer scripts.
 *
 * @return void
 */
function remove_customizer_scripts() : void {
	wp_dequeue_script( 'twentytwenty-customize' );
	wp_dequeue_script( 'twentytwenty-color-calculations' );
	wp_dequeue_script( 'twentytwenty-customize-controls' );
}

/**
 * Removes parent Customizer controls.
 *
 * @since 1.0.1
 * @param WP_Customize_Manager $wp_customize The customizer manager instance.
 * @return void
 */
function register_customizer( WP_Customize_Manager $wp_customize ) : void {
	$controls = [
		'retina_logo',
		'accent_hue_active',
		'header_footer_background_color',
		'enable_header_search',
		'cover_template_separator_1',
		'cover_template_overlay_background_color',
		'cover_template_overlay_text_color',
		'cover_template_overlay_opacity',
	];
	foreach ( $controls as $control ) {
		$wp_customize->remove_control( $control );
	}

	/**
	 * Removes Jetpack from TranslatePress translation options.
	 *
	 * @param bool   $return
	 * @param string $translation
	 * @param string $text
	 * @param string $domain
	 * @return bool
	 */
	function skip_jetpack_translation( $return, $translation, $text, string $domain ) {
		if ( 'jetpack' === $domain ) {
			return true;
		}
		return $return;
	}
}
