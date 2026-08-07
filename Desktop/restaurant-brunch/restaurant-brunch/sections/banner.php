<?php
if ( ! get_theme_mod( 'restaurant_brunch_enable_banner_section', false ) ) {
	return;
}

$restaurant_brunch_banner_category = get_theme_mod( 'restaurant_brunch_banner_slider_category', 'slider' );

$restaurant_brunch_args = array(
	'post_type'           => 'post',
	'category_name'       => sanitize_text_field( $restaurant_brunch_banner_category ),
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => true,
);

$restaurant_brunch_query = new WP_Query( $restaurant_brunch_args );

if ( $restaurant_brunch_query->have_posts() ) :
	$restaurant_brunch_button_label = get_theme_mod( 'restaurant_brunch_banner_button_label' );
	$restaurant_brunch_button_link  = get_theme_mod( 'restaurant_brunch_banner_button_link' );
	$restaurant_brunch_button_link  = ! empty( $restaurant_brunch_button_link ) ? esc_url( $restaurant_brunch_button_link ) : '';
	?>

	<section id="restaurant_brunch_banner_section" class="banner-section banner-style-1 wow fadeInDown" data-wow-duration="1.5s">
		<?php if ( is_customize_preview() ) : restaurant_brunch_section_link( 'restaurant_brunch_banner_section' ); endif; ?>
		<div class="banner-section-wrapper">
			<div class="asterthemes-banner-wrapper banner-slider restaurant-brunch-carousel-navigation" data-slick='{"autoplay": true }'>
				<?php
				while ( $restaurant_brunch_query->have_posts() ) :
					$restaurant_brunch_query->the_post();
					?>
					<div class="banner-single-outer">
						<div class="banner-single">
							<div class="banner-img">
								<?php
								if ( has_post_thumbnail() ) {
									the_post_thumbnail( 'full' );
								} else {
									echo '<img src="' . esc_url( get_template_directory_uri() . '/resource/img/default.png' ) . '" alt="' . esc_attr( get_the_title() ) . '">';
								}
								?>
							</div>
							<div class="banner-caption">
								<div class="asterthemes-wrapper">
									<div class="banner-catption-wrapper">
										<h2 class="banner-caption-title">
											<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
										</h2>
										<div class="mag-post-excerpt"><?php the_excerpt(); ?></div>
										<?php if ( ! empty( $restaurant_brunch_button_label ) && ! empty( $restaurant_brunch_button_link ) ) : ?>
											<div class="banner-slider-btn">
												<a href="<?php echo esc_url( $restaurant_brunch_button_link ); ?>" class="asterthemes-button">
													<?php echo esc_html( $restaurant_brunch_button_label ); ?>
												</a>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
	</section>

<?php endif; ?>