<?php
if ( ! get_theme_mod( 'restaurant_brunch_enable_service_section', false ) ) {
	return;
}

$restaurant_brunch_category = get_theme_mod( 'restaurant_brunch_category', 'products' );

// Query posts from selected category
$restaurant_brunch_args = array(
	'post_type'           => 'post',
	'posts_per_page' 	  => -1,
	'ignore_sticky_posts' => true,
);

if ( ! empty( $restaurant_brunch_category ) ) {
	$restaurant_brunch_args['category_name'] = $restaurant_brunch_category;
}

$restaurant_brunch_args = apply_filters( 'restaurant_brunch_service_section_args', $restaurant_brunch_args );

restaurant_brunch_render_service_section( $restaurant_brunch_args );

function restaurant_brunch_render_service_section() {
	$restaurant_brunch_heading = get_theme_mod( 'restaurant_brunch_trending_post_heading' );
	?>
	<section id="restaurant_brunch_service_section" class="asterthemes-frontpage-section service-section service-style-1">
		<?php if ( is_customize_preview() ) restaurant_brunch_section_link( 'restaurant_brunch_service_section' ); ?>

		<div class="asterthemes-wrapper service-contact-inner">
			<div class="category-header">
				<?php if ( ! empty( $restaurant_brunch_heading ) ) : ?>
					<h2><?php echo esc_html( $restaurant_brunch_heading ); ?></h2>
				<?php endif; ?>
			</div>

			<div class="category-section">
				<?php if (class_exists('woocommerce')) { ?>
				<?php
				$restaurant_brunch_args = array(
					'orderby'    => 'title',
					'order'      => 'ASC',
					'hide_empty' => true, // Only fetch categories with products
					'parent'     => 0
				);
				$restaurant_brunch_product_categories = get_terms('product_cat', $restaurant_brunch_args);

				if (!empty($restaurant_brunch_product_categories)) {
					foreach ($restaurant_brunch_product_categories as $restaurant_brunch_product_category) {
					if ($restaurant_brunch_product_category->count > 0) {
						$restaurant_brunch_product_cat_id = $restaurant_brunch_product_category->term_id;
						$restaurant_brunch_cat_link = get_term_link($restaurant_brunch_product_category);
						$restaurant_brunch_thumbnail_id = get_term_meta($restaurant_brunch_product_cat_id, 'thumbnail_id', true);
						$restaurant_brunch_image_url = wp_get_attachment_url($restaurant_brunch_thumbnail_id);
						if (!$restaurant_brunch_image_url) {
						$restaurant_brunch_image_url = get_template_directory_uri() . '/resource/img/default.png';
						}
						?>
						<div class="item wow zoomIn" data-wow-delay="0.5s" data-wow-duration="2s">
							<div class="category-image">
								<img src="<?php echo esc_url($restaurant_brunch_image_url); ?>" alt="<?php echo esc_attr($restaurant_brunch_product_category->name); ?>" />
							</div>
							<div class="category-content">
								<a href="<?php echo esc_url($restaurant_brunch_cat_link); ?>" class="category-name-btn">
								<span class="category-name"><?php echo esc_html($restaurant_brunch_product_category->name); ?></span>
								</a>
							</div>
						</div>
					<?php }
					}
				}?>
				<?php } ?>
			</div>

		</div>
	</section>
	<?php
}
?>
