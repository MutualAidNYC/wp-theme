<?php
/**
 * Template Name: Sidebar Template
 * Template Post Type: page
 *
 * @package WordPress
 * @subpackage MutualAidNYC
 * @since 1.0.4
 */

get_header();
?>

<main id="site-content" role="main">

	<?php
	if ( have_posts() ) :
		the_post();
		?>
		<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
			<?php get_template_part( 'template-parts/header-cover' ); ?>

			<div class="post-inner" id="post-inner">

				<div class="entry-content">
					<?php the_content(); ?>

					<?php edit_post_link(); ?>
				</div>

				<aside class="page-sidebar">
					<?php dynamic_sidebar( 'page-sidebar' ); ?>
				</aside>
			</div>
		</article><!-- .post -->
		<?php
	endif;
	?>

</main><!-- #site-content -->

<?php get_template_part( 'template-parts/footer-menus-widgets' ); ?>

<?php get_footer(); ?>
