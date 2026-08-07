<?php
function restaurant_brunch_get_all_google_fonts() {
    $restaurant_brunch_webfonts_json = get_template_directory() . '/theme-library/google-webfonts.json';
    if ( ! file_exists( $restaurant_brunch_webfonts_json ) ) {
        return array();
    }

    $restaurant_brunch_fonts_json_data = file_get_contents( $restaurant_brunch_webfonts_json );
    if ( false === $restaurant_brunch_fonts_json_data ) {
        return array();
    }

    $restaurant_brunch_all_fonts = json_decode( $restaurant_brunch_fonts_json_data, true );
    if ( json_last_error() !== JSON_ERROR_NONE ) {
        return array();
    }

    $restaurant_brunch_google_fonts = array();
    foreach ( $restaurant_brunch_all_fonts as $restaurant_brunch_font ) {
        $restaurant_brunch_google_fonts[ $restaurant_brunch_font['family'] ] = array(
            'family'   => $restaurant_brunch_font['family'],
            'variants' => $restaurant_brunch_font['variants'],
        );
    }
    return $restaurant_brunch_google_fonts;
}

function restaurant_brunch_get_all_google_font_families() {
    $restaurant_brunch_google_fonts  = restaurant_brunch_get_all_google_fonts();
    $restaurant_brunch_font_families = array();
    foreach ( $restaurant_brunch_google_fonts as $restaurant_brunch_font ) {
        $restaurant_brunch_font_families[ $restaurant_brunch_font['family'] ] = $restaurant_brunch_font['family'];
    }
    return $restaurant_brunch_font_families;
}

function restaurant_brunch_get_fonts_url() {
    $restaurant_brunch_fonts_url = '';
    $restaurant_brunch_fonts     = array();

    $restaurant_brunch_all_fonts = restaurant_brunch_get_all_google_fonts();

    if ( ! empty( get_theme_mod( 'restaurant_brunch_site_title_font', 'Cormorant Garamond' ) ) ) {
        $restaurant_brunch_fonts[] = get_theme_mod( 'restaurant_brunch_site_title_font', 'Cormorant Garamond' );
    }

    if ( ! empty( get_theme_mod( 'restaurant_brunch_site_description_font', 'Open Sans' ) ) ) {
        $restaurant_brunch_fonts[] = get_theme_mod( 'restaurant_brunch_site_description_font', 'Open Sans' );
    }

    if ( ! empty( get_theme_mod( 'restaurant_brunch_header_font', 'Cormorant Garamond' ) ) ) {
        $restaurant_brunch_fonts[] = get_theme_mod( 'restaurant_brunch_header_font', 'Cormorant Garamond' );
    }

    if ( ! empty( get_theme_mod( 'restaurant_brunch_content_font', 'Open Sans' ) ) ) {
        $restaurant_brunch_fonts[] = get_theme_mod( 'restaurant_brunch_content_font', 'Open Sans' );
    }

    $restaurant_brunch_fonts = array_unique( $restaurant_brunch_fonts );

    foreach ( $restaurant_brunch_fonts as $restaurant_brunch_font ) {
        $restaurant_brunch_variants      = $restaurant_brunch_all_fonts[ $restaurant_brunch_font ]['variants'];
        $restaurant_brunch_font_family[] = $restaurant_brunch_font . ':' . implode( ',', $restaurant_brunch_variants );
    }

    $restaurant_brunch_query_args = array(
        'family' => urlencode( implode( '|', $restaurant_brunch_font_family ) ),
    );

    if ( ! empty( $restaurant_brunch_font_family ) ) {
        $restaurant_brunch_fonts_url = add_query_arg( $restaurant_brunch_query_args, 'https://fonts.googleapis.com/css' );
    }

    return $restaurant_brunch_fonts_url;
}