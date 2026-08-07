<?php
/**
 * Getting Started Page.
 *
 * @package restaurant_brunch
 */


if( ! function_exists( 'restaurant_brunch_getting_started_menu' ) ) :
/**
 * Adding Getting Started Page in admin menu
 */
function restaurant_brunch_getting_started_menu(){	
	add_theme_page(
		__( 'Getting Started', 'restaurant-brunch' ),
		__( 'Getting Started', 'restaurant-brunch' ),
		'manage_options',
		'restaurant-brunch-getting-started',
		'restaurant_brunch_getting_started_page'
	);
}
endif;
add_action( 'admin_menu', 'restaurant_brunch_getting_started_menu' );

if( ! function_exists( 'restaurant_brunch_getting_started_admin_scripts' ) ) :
/**
 * Load Getting Started styles in the admin
 */
function restaurant_brunch_getting_started_admin_scripts( $hook ){
	// Load styles only on our page
	if( 'appearance_page_restaurant-brunch-getting-started' != $hook ) return;

    wp_enqueue_style( 'restaurant-brunch-getting-started', get_template_directory_uri() . '/resource/css/getting-started.css', false, RESTAURANT_BRUNCH_THEME_VERSION );

    wp_enqueue_script( 'restaurant-brunch-getting-started', get_template_directory_uri() . '/resource/js/getting-started.js', array( 'jquery' ), RESTAURANT_BRUNCH_THEME_VERSION, true );
}
endif;
add_action( 'admin_enqueue_scripts', 'restaurant_brunch_getting_started_admin_scripts' );

if( ! function_exists( 'restaurant_brunch_getting_started_page' ) ) :
/**
 * Callback function for admin page.
*/
function restaurant_brunch_getting_started_page(){ 
	$restaurant_brunch_theme = wp_get_theme();?>
	<div class="wrap getting-started">
		<div class="intro-wrap">
			<div class="intro cointaner">
				<div class="intro-content">
					<h3><?php echo esc_html( 'Welcome to', 'restaurant-brunch' );?> <span class="theme-name"><?php echo esc_html( RESTAURANT_BRUNCH_THEME_NAME ); ?></span></h3>
					<p class="about-text">
						<?php
						// Remove last sentence of description.
						$restaurant_brunch_description = explode( '. ', $restaurant_brunch_theme->get( 'Description' ) );

						$restaurant_brunch_description = implode( '. ', $restaurant_brunch_description );

						echo esc_html( $restaurant_brunch_description . '' );
					?></p>
					<div class="btns-getstart">
						<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"target="_blank" class="button button-primary"><?php esc_html_e( 'Customize', 'restaurant-brunch' ); ?></a>
						<a class="button button-primary" href="<?php echo esc_url( 'https://wordpress.org/support/theme/restaurant-brunch/reviews/#new-post' ); ?>" title="<?php esc_attr_e( 'Visit the Review', 'restaurant-brunch' ); ?>" target="_blank">
							<?php esc_html_e( 'Review', 'restaurant-brunch' ); ?>
						</a>
						<a class="button button-primary" href="<?php echo esc_url( 'https://wordpress.org/support/theme/restaurant-brunch/' ); ?>" title="<?php esc_attr_e( 'Visit the Support', 'restaurant-brunch' ); ?>" target="_blank">
							<?php esc_html_e( 'Contact Support', 'restaurant-brunch' ); ?>
						</a>
			        </div>
			        <div class="btns-wizard">
						<a class="wizard" href="<?php echo esc_url( admin_url( 'themes.php?page=restaurantbrunch-wizard' ) ); ?>"target="_blank" class="button button-primary"><?php esc_html_e( 'One Click Demo Setup', 'restaurant-brunch' ); ?></a>
					</div>
				</div>
				<div class="intro-img">
					<img src="<?php echo esc_url(get_template_directory_uri()) .'/screenshot.png'; ?>" />
				</div>
				
			</div>
		</div>

		<div class="cointaner panels">
			<ul class="inline-list">
				<li class="current">
                    <a id="help" href="javascript:void(0);">
                        <?php esc_html_e( 'Getting Started', 'restaurant-brunch' ); ?>
                    </a>
                </li>
				<li>
                    <a id="free-pro-panel" href="javascript:void(0);">
                        <?php esc_html_e( 'Free Vs Pro', 'restaurant-brunch' ); ?>
                    </a>
                </li>
			</ul>
			<div id="panel" class="panel">
				<?php require get_template_directory() . '/theme-library/getting-started/tabs/help-panel.php'; ?>
				<?php require get_template_directory() . '/theme-library/getting-started/tabs/free-vs-pro-panel.php'; ?>
				<?php require get_template_directory() . '/theme-library/getting-started/tabs/link-panel.php'; ?>
			</div>
		</div>
	</div>
	<?php
}
endif;
