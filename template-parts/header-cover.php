<?php
/**
 * Displays the content when the cover template is used.
 *
 * Taken from TwentyTwenty content-cover.php.
 *
 * @package WordPress
 * @subpackage MutualAidNYC
 * @since 1.0.4
 */

// On the cover page template, output the cover header.
$cover_header_style   = '';
$cover_header_classes = '';

$color_overlay_style   = '';
$color_overlay_classes = '';

$image_url = ! post_password_required() ? get_the_post_thumbnail_url( get_the_ID(), 'twentytwenty-fullscreen' ) : '';

if ( $image_url ) {
	$cover_header_style   = ' style="background-image: url( ' . esc_url( $image_url ) . ' );"';
	$cover_header_classes = ' bg-image';
}

// Get the color used for the color overlay.
$color_overlay_color = get_theme_mod( 'cover_template_overlay_background_color' );
if ( $color_overlay_color ) {
	$color_overlay_style = ' style="color: ' . esc_attr( $color_overlay_color ) . ';"';
} else {
	$color_overlay_style = '';
}

// Get the fixed background attachment option.
if ( get_theme_mod( 'cover_template_fixed_background', true ) ) {
	$cover_header_classes .= ' bg-attachment-fixed';
}

// Get the opacity of the color overlay.
$color_overlay_opacity  = get_theme_mod( 'cover_template_overlay_opacity' );
$color_overlay_opacity  = ( false === $color_overlay_opacity ) ? 80 : $color_overlay_opacity;
$color_overlay_classes .= ' opacity-' . $color_overlay_opacity;
?>

	<div class="cover-header <?php echo $cover_header_classes; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static output ?>"<?php echo $cover_header_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to double check this, but for now, we want to pass PHPCS ;) ?>>
		<div class="cover-header-inner-wrapper screen-height">
			<div class="cover-header-inner">
				<div class="cover-color-overlay color-accent<?php echo esc_attr( $color_overlay_classes ); ?>"<?php echo $color_overlay_style; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- We need to double check this, but for now, we want to pass PHPCS ;) ?>></div>

					<header class="entry-header has-text-align-center">
						<div class="entry-header-inner section-inner medium">

							<?php

							/**
							 * Allow child themes and plugins to filter the display of the categories in the article header.
							 *
							 * @since Twenty Twenty 1.0
							 *
							 * @param bool Whether to show the categories in article header, Default true.
							 */
							$show_categories = apply_filters( 'twentytwenty_show_categories_in_entry_header', true );

							if ( true === $show_categories && has_category() ) {
								?>

								<div class="entry-categories">
									<span class="screen-reader-text"><?php esc_html_e( 'Categories', 'mutualaidnyc' ); ?></span>
									<div class="entry-categories-inner">
										<?php the_category( ' ' ); ?>
									</div><!-- .entry-categories-inner -->
								</div><!-- .entry-categories -->

								<?php
							}

							?>

						</div><!-- .entry-header-inner -->
					</header><!-- .entry-header -->

			</div><!-- .cover-header-inner -->
		</div><!-- .cover-header-inner-wrapper -->
	</div><!-- .cover-header -->
