<?php
/**
 * Template Name: Resources Template
 * Template Post Type: page
 *
 * @package mutualaidnyc
 * @since 1.0
 */

get_header();
?>

<main id="site-content" role="main">

	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();

			get_template_part( 'template-parts/content' );
		}
	}

	if ( class_exists( 'AirpressQuery' ) ) {
		$needs_query = new AirpressQuery( 'Ref - Need', 0 );
		$needs_query->addFilter( 'NOT({Resources} = BLANK())' );
		$needs_query->addFilter( 'NOT({Need} = "-Not Listed")' );
		$needs_query->sort( 'Need' );

		$resources_query = new AirpressQuery( 'Resources', 0 );
		$resources_query->addFilter( '{Publish Status of Resource} = "Published"' );

		$needs = new AirpressCollection( $needs_query );
		$needs->populateRelatedField( 'Resources', $resources_query );

		echo '<ul>';
		foreach ( $needs as $need ) {
			printf( '<li>%s <ul>', esc_html( $need['Need'] ) );
			foreach ( $need['Resources'] as $resource ) {
				printf(
					'<li><a href="%2$s">%1$s</a></li>',
					esc_html( $resource['Resource Title'] ),
					esc_url( $resource['Link to Resource'] ?? '' ),
				);
			}
			echo '</ul></li>';
		}
		echo '</ul>';
	}
	?>

</main><!-- #site-content -->

<?php
get_footer();
