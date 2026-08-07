<?php
/**
 * Dynamic CSS
 */
function restaurant_brunch_dynamic_css() {
	$restaurant_brunch_primary_color = get_theme_mod( 'primary_color', '#CBAB00' );

	$restaurant_brunch_site_title_font       = get_theme_mod( 'restaurant_brunch_site_title_font', 'Cormorant Garamond' );
	$restaurant_brunch_site_description_font = get_theme_mod( 'restaurant_brunch_site_description_font', 'Open Sans' );
	$restaurant_brunch_header_font           = get_theme_mod( 'restaurant_brunch_header_font', 'Cormorant Garamond' );
	$restaurant_brunch_content_font          = get_theme_mod( 'restaurant_brunch_content_font', 'Open Sans' );

	// Enqueue Google Fonts
	$restaurant_brunch_fonts_url = restaurant_brunch_get_fonts_url();
	if ( ! empty( $restaurant_brunch_fonts_url ) ) {
		wp_enqueue_style( 'restaurant-brunch-google-fonts', esc_url( $restaurant_brunch_fonts_url ), array(), null );
	}

	$restaurant_brunch_custom_css  = '';
	$restaurant_brunch_custom_css .= '
    /* Color */
    :root {
        --primary-color: ' . esc_attr( $restaurant_brunch_primary_color ) . ';
        --header-text-color: ' . esc_attr( '#' . get_header_textcolor() ) . ';
    }
    ';

	$restaurant_brunch_custom_css .= '
    /* Typography */
    :root {
        --font-heading: "' . esc_attr( $restaurant_brunch_header_font ) . '", serif;
        --font-main: -apple-system, BlinkMacSystemFont, "' . esc_attr( $restaurant_brunch_content_font ) . '", "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    body,
	button, input, select, optgroup, textarea, p {
        font-family: "' . esc_attr( $restaurant_brunch_content_font ) . '", serif;
	}

	.site-identity p.site-title, h1.site-title a, h1.site-title, p.site-title a, .site-branding h1.site-title a, header.site-header .header-main-wrapper:not(.transparent-header) .bottom-header-outer-wrapper .bottom-header-part .bottom-header-part-wrapper .site-branding .site-identity .site-title .banner-text {
        font-family: "' . esc_attr( $restaurant_brunch_site_title_font ) . '", serif;
	}
    
	p.site-description {
        font-family: "' . esc_attr( $restaurant_brunch_site_description_font ) . '", serif !important;
	}
    ';

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_dynamic_css', 99 );