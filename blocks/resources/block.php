<?php
/**
 * Server-side functions for the Resources Block.
 *
 * @package mutualaidnyc
 */

namespace MutualAidNYC\Blocks\Resources;

use const MutualAidNYC\Blocks\ADD_BLOCK_HOOK;
use AirpressQuery;
use AirpressCollection;
use WPCom_GHF_Markdown_Parser;
use TRP_Translate_Press;

add_action( ADD_BLOCK_HOOK, __NAMESPACE__ . '\\block_init' );

/**
 * Block initialization.
 *
 * @return void
 */
function block_init() {
	register_block_type(
		'mutualaidnyc/resources',
		[
			'render_callback' => __NAMESPACE__ . '\\render_callback',
		]
	);
}

/**
 * Render callback for Resources block.
 *
 * @param array $attributes Attributes for block.
 * @return string
 */
function render_callback( array $attributes ) : string {
	if ( ! class_exists( 'AirpressQuery' ) ) {
		return sprintf(
			'<p>%s</p>',
			esc_html__( 'Needs the AirPress plugin active to function!', 'mutualaidnyc' )
		);
	}

	$needs_query = new AirpressQuery( 'Ref - Need', 0 );
	$needs_query->addFilter( 'NOT({Resources} = BLANK())' );
	$needs_query->addFilter( 'NOT({Need} = "-Not Listed")' );
	$needs_query->sort( 'Need' );

	$resources_query = new AirpressQuery( 'Resources', 0 );
	$resources_query->addFilter( '{Publish Status of Resource} = "Published"' );

	$trp = TRP_Translate_Press::get_trp_instance();
	$url_converter = $trp->get_component( 'url_converter' );
	$language_code = $url_converter->get_lang_from_url_string();

	if ($language_code) {
		$language_handler = $trp->get_component('languages');
		$language_name = $language_handler->get_language_names(array($language_code), 'english_name')[$language_code];
		$resources_query->view("Language ".$language_name." site view");
	} else {
		$resources_query->sort( 'Display First', 'desc' );
	}
	$needs = new AirpressCollection( $needs_query );
	$needs->populateRelatedField( 'Resources', $resources_query );

	$html = sprintf(
		'<div class="wp-block-resources %s">',
		esc_attr( $attributes['className'] ?? '' )
	);

	$markdown_parser = null;
	if ( function_exists( 'jetpack_require_lib' ) ) {
		jetpack_require_lib( 'markdown' );
		$markdown_parser = new WPCom_GHF_Markdown_Parser();
	}

	foreach ( $needs as $need ) {
		if ( count( $need['Resources'] ) === 0 ) {
			continue;
		}
		if ($language_code) {
			$need_name = $need[$language_name.' Translation'];
		} else {
		       $need_name = $need['Need'];
		}
		$anchor = preg_replace( '/[^a-z0-9]+/', '+', strtolower($need_name));
		$anchor = trim( $anchor, '+' );
		$html  .= '<details class="resources__need">';
		$html  .= sprintf(
			'<summary class="resources__need-title" id="%1$s">%2$s</summary>',
			esc_attr( $anchor ),
			esc_html( $need_name )
		);
		$html  .= '<div class="resources__item-wrapper">';
		foreach ( $need['Resources'] as $resource ) {
			$html .= sprintf(
				'<div class="resources__item">
					<span class="resources__tag resources__tag--%5$s">%4$s</span>
					<h3 class="resources__item-title">%1$s</h3>
					%2$s
					<p><a href="%3$s" class="resources__item-link">%3$s</a></p>
				</div>',
				esc_html( $resource['Resource Title'] ),
				wp_kses_post( markdown_to_html( $resource['Resource Details'], $markdown_parser ) ),
				esc_url( $resource['Link to Resource'] ?? '' ),
				esc_html( $resource['Resource Type'] ?? '' ),
				esc_attr( strtolower( $resource['Resource Type'] ?? '' ) )
			);
		}
		$html .= '</div>';
		$html .= '</details>';
	}
	$html .= '</div>';

	return $html;
}

/**
 * Translates markdown to HTML using Jetpack parser.
 *
 * @param string                    $markdown The markdown from the API.
 * @param WPCom_GHF_Markdown_Parser $parser   The parser to use.
 * @return string
 */
function markdown_to_html( string $markdown, $parser = null ) : string {
	$html = '';
	if ( $parser ) {
		$html = $parser->transform( $markdown );
	} else {
		$html = $markdown;
	}

	return wpautop( wptexturize( $html ) );
}
