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
		'<div class="%s">',
		esc_attr( $attributes['className'] ?? '' )
	);

	$html .= '<ul>';
	foreach ( $needs as $need ) {
		$html .= sprintf( '<li>%s <ul>', esc_html( $need['Need'] ) );
		foreach ( $need['Resources'] as $resource ) {
			$html .= sprintf(
				'<li><a href="%2$s">%1$s</a></li>',
				esc_html( $resource['Resource Title'] ),
				esc_url( $resource['Link to Resource'] ?? '' ),
			);
		}
		$html .= '</ul></li>';
	}
	$html .= '</ul>';
	$html .= '</div>';

	return $html;
}
