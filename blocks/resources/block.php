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
		$anchor = preg_replace( '/[^a-z0-9]+/', '+', strtolower( $need['Need'] ) );
		$anchor = trim( $anchor, '+' );
		$html  .= '<details class="resources__need">';
		$html  .= sprintf(
			'<summary class="resources__need-title" id="%1$s">%2$s</summary>',
			esc_attr( $anchor ),
			esc_html( $need['Need'] )
		);
		$html  .= '<div class="resources__item-wrapper">';
		foreach ( $need['Resources'] as $resource ) {
			$html .= sprintf(
				'<div class="resources__item">
					<span class="resources__tag resources__tag--%5$s">%4$s</span>
					<p class="resources__item-title">%1$s</p>
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
