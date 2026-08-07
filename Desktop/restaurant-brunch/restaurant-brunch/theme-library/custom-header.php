<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package restaurant_brunch
 */

function restaurant_brunch_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'restaurant_brunch_custom_header_args', array(
		'default-text-color'     => 'fff',
		'header-text' 			 =>	false,
		'width'                  => 1360,
		'height'                 => 110,
		'flex-width'         	=> true,
        'flex-height'        	=> true,
		'wp-head-callback'       => 'restaurant_brunch_header_style',
	) ) );
}

add_action( 'after_setup_theme', 'restaurant_brunch_custom_header_setup' );

if ( ! function_exists( 'restaurant_brunch_header_style' ) ) :

add_action( 'wp_enqueue_scripts', 'restaurant_brunch_header_style' );
function restaurant_brunch_header_style() {
	if ( get_header_image() ) :
	$restaurant_brunch_custom_css = "
            .bottom-header-outer-wrapper, header.site-header .header-main-wrapper .bottom-header-outer-wrapper .bottom-header-part, header.site-header .header-main-wrapper .bottom-header-outer-wrapper .bottom-header-part .bottom-header-part-wrapper.hello.sticky-header.is-sticky {
			background-image:url('".esc_url(get_header_image())."') !important;
			background-position: center top;
			background-size: cover !important;
		}";
	   	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
	endif;
}
endif;