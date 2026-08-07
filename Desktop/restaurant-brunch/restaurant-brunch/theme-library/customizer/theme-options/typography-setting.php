<?php
/**
 * Typography Setting
 *
 * @package restaurant_brunch
 */

// Typography Setting
$wp_customize->add_section(
    'restaurant_brunch_typography_setting',
    array(
        'panel' => 'restaurant_brunch_theme_options',
        'title' => esc_html__( 'Typography Setting', 'restaurant-brunch' ),
    )
);

$wp_customize->add_setting(
    'restaurant_brunch_site_title_font',
    array(
        'default'           => 'Cormorant Garamond',
        'sanitize_callback' => 'restaurant_brunch_sanitize_google_fonts',
    )
);

$wp_customize->add_control(
    'restaurant_brunch_site_title_font',
    array(
        'label'    => esc_html__( 'Site Title Font Family', 'restaurant-brunch' ),
        'section'  => 'restaurant_brunch_typography_setting',
        'settings' => 'restaurant_brunch_site_title_font',
        'type'     => 'select',
        'choices'  => restaurant_brunch_get_all_google_font_families(),
    )
);

// Typography - Site Description Font.
$wp_customize->add_setting(
	'restaurant_brunch_site_description_font',
	array(
		'default'           => 'Open Sans',
		'sanitize_callback' => 'restaurant_brunch_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_site_description_font',
	array(
		'label'    => esc_html__( 'Site Description Font Family', 'restaurant-brunch' ),
		'section'  => 'restaurant_brunch_typography_setting',
		'settings' => 'restaurant_brunch_site_description_font',
		'type'     => 'select',
		'choices'  => restaurant_brunch_get_all_google_font_families(),
	)
);

// Typography - Header Font.
$wp_customize->add_setting(
	'restaurant_brunch_header_font',
	array(
		'default'           => 'Cormorant Garamond',
		'sanitize_callback' => 'restaurant_brunch_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_header_font',
	array(
		'label'    => esc_html__( 'Heading Font Family', 'restaurant-brunch' ),
		'section'  => 'restaurant_brunch_typography_setting',
		'settings' => 'restaurant_brunch_header_font',
		'type'     => 'select',
		'choices'  => restaurant_brunch_get_all_google_font_families(),
	)
);

// Typography - Body Font.
$wp_customize->add_setting(
	'restaurant_brunch_content_font',
	array(
		'default'           => 'Open Sans',
		'sanitize_callback' => 'restaurant_brunch_sanitize_google_fonts',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_content_font',
	array(
		'label'    => esc_html__( 'Content Font Family', 'restaurant-brunch' ),
		'section'  => 'restaurant_brunch_typography_setting',
		'settings' => 'restaurant_brunch_content_font',
		'type'     => 'select',
		'choices'  => restaurant_brunch_get_all_google_font_families(),
	)
);