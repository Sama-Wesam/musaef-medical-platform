<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package restaurant_brunch
 */

get_header();

$restaurant_brunch_column = get_theme_mod( 'restaurant_brunch_archive_column_layout', 'column-1' );
?>
<main id="primary" class="site-main">
	<?php if ( have_posts() ) : ?>
		<div class="restaurant-brunch-archive-layout grid-layout <?php echo esc_attr( $restaurant_brunch_column ); ?>">
			<?php
			/* Start the Loop */
			while ( have_posts() ) : the_post();

			get_template_part( 'template-parts/content', get_post_format() );

			endwhile;
			?>
		</div>
		<?php
		do_action( 'restaurant_brunch_posts_pagination' );
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	?>
</main><!-- #main -->
<?php
if ( restaurant_brunch_is_sidebar_enabled() ) {
	get_sidebar();
}
get_footer();