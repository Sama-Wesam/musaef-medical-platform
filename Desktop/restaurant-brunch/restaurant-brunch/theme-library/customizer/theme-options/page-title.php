<?php
/**
 * Pige Title Options
 *
 * @package restaurant_brunch
 */



$wp_customize->add_section(
	'restaurant_brunch_page_title_options',
	array(
		'panel' => 'restaurant_brunch_theme_options',
		'title' => esc_html__( 'Page Title', 'restaurant-brunch' ),
	)
);

$wp_customize->add_setting(
    'restaurant_brunch_page_header_visibility',
    array(
        'default'           => 'all-devices',
        'sanitize_callback' => 'restaurant_brunch_sanitize_select',
    )
);

$wp_customize->add_control(
    new WP_Customize_Control(
        $wp_customize,
        'restaurant_brunch_page_header_visibility',
        array(
            'label'    => esc_html__( 'Page Header Visibility', 'restaurant-brunch' ),
            'type'     => 'select',
            'section'  => 'restaurant_brunch_page_title_options',
            'settings' => 'restaurant_brunch_page_header_visibility',
            'priority' => 10,
            'choices'  => array(
                'all-devices'        => esc_html__( 'Show on all devices', 'restaurant-brunch' ),
                'hide-tablet'        => esc_html__( 'Hide on Tablet', 'restaurant-brunch' ),
                'hide-mobile'        => esc_html__( 'Hide on Mobile', 'restaurant-brunch' ),
                'hide-tablet-mobile' => esc_html__( 'Hide on Tablet & Mobile', 'restaurant-brunch' ),
                'hide-all-devices'   => esc_html__( 'Hide on all devices', 'restaurant-brunch' ),
            ),
        )
    )
);


$wp_customize->add_setting( 'restaurant_brunch_page_title_background_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_page_title_background_separator', array(
	'label' => __( 'Page Title BG Image & Color Setting', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_page_title_options',
	'settings' => 'restaurant_brunch_page_title_background_separator',
)));


$wp_customize->add_setting(
	'restaurant_brunch_page_header_style',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
		'default'           => False,
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_page_header_style',
		array(
			'label'   => esc_html__('Page Title Background Image', 'restaurant-brunch'),
			'section' => 'restaurant_brunch_page_title_options',
		)
	)
);

$wp_customize->add_setting( 'restaurant_brunch_page_header_background_image', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'restaurant_brunch_page_header_background_image', array(
    'label'    => __( 'Background Image', 'restaurant-brunch' ),
    'section'  => 'restaurant_brunch_page_title_options',
	'description' => __('Choose either a background image or a color. If a background image is selected, the background color will not be visible.', 'restaurant-brunch'),
    'settings' => 'restaurant_brunch_page_header_background_image',
	'active_callback' => 'restaurant_brunch_is_pagetitle_bcakground_image_enabled',
)));


$wp_customize->add_setting('restaurant_brunch_page_header_image_height', array(
	'default'           => 200,
	'sanitize_callback' => 'restaurant_brunch_sanitize_range_value',
));

$wp_customize->add_control(new Restaurant_Brunch_Customize_Range_Control($wp_customize, 'restaurant_brunch_page_header_image_height', array(
		'label'       => __('Image Height', 'restaurant-brunch'),
		'section'     => 'restaurant_brunch_page_title_options',
		'settings'    => 'restaurant_brunch_page_header_image_height',
		'active_callback' => 'restaurant_brunch_is_pagetitle_bcakground_image_enabled',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 1000,
			'step' => 5,
		),
)));


$wp_customize->add_setting('restaurant_brunch_page_title_background_color_setting', array(
    'default' => '',
    'sanitize_callback' => 'sanitize_hex_color',
));

$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'restaurant_brunch_page_title_background_color_setting', array(
    'label' => __('Page Title Background Color', 'restaurant-brunch'),
    'section' => 'restaurant_brunch_page_title_options',
)));

$wp_customize->add_setting('restaurant_brunch_pagetitle_height', array(
    'default'           => 50,
    'sanitize_callback' => 'restaurant_brunch_sanitize_range_value',
));

$wp_customize->add_control(new Restaurant_Brunch_Customize_Range_Control($wp_customize, 'restaurant_brunch_pagetitle_height', array(
    'label'       => __('Set Height', 'restaurant-brunch'),
    'description' => __('This setting controls the page title height when no background image is set. If a background image is set, this setting will not apply.', 'restaurant-brunch'),
    'section'     => 'restaurant_brunch_page_title_options',
    'settings'    => 'restaurant_brunch_pagetitle_height',
    'input_attrs' => array(
        'min'  => 0,
        'max'  => 300,
        'step' => 5,
    ),
)));


$wp_customize->add_setting( 'restaurant_brunch_page_title_style_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_page_title_style_separator', array(
	'label' => __( 'Page Title Styling Setting', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_page_title_options',
	'settings' => 'restaurant_brunch_page_title_style_separator',
)));

$wp_customize->add_setting( 'restaurant_brunch_page_header_heading_tag', array(
	'default'   => 'h1',
	'sanitize_callback' => 'restaurant_brunch_sanitize_select',
) );

$wp_customize->add_control( 'restaurant_brunch_page_header_heading_tag', array(
	'label'   => __( 'Page Title Heading Tag', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_page_title_options',
	'type'    => 'select',
	'choices' => array(
		'h1' => __( 'H1', 'restaurant-brunch' ),
		'h2' => __( 'H2', 'restaurant-brunch' ),
		'h3' => __( 'H3', 'restaurant-brunch' ),
		'h4' => __( 'H4', 'restaurant-brunch' ),
		'h5' => __( 'H5', 'restaurant-brunch' ),
		'h6' => __( 'H6', 'restaurant-brunch' ),
		'p' => __( 'p', 'restaurant-brunch' ),
		'a' => __( 'a', 'restaurant-brunch' ),
		'div' => __( 'div', 'restaurant-brunch' ),
		'span' => __( 'span', 'restaurant-brunch' ),
	),
) );

$wp_customize->add_setting('restaurant_brunch_page_header_layout', array(
	'default' => 'left',
	'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control('restaurant_brunch_page_header_layout', array(
	'label' => __('Style', 'restaurant-brunch'),
	'section' => 'restaurant_brunch_page_title_options',
	'description' => __('"Flex Layout Style" wont work below 600px (mobile media)', 'restaurant-brunch'),
	'settings' => 'restaurant_brunch_page_header_layout',
	'type' => 'radio',
	'choices' => array(
		'left' => __('Classic', 'restaurant-brunch'),
		'right' => __('Aligned Right', 'restaurant-brunch'),
		'center' => __('Centered Focus', 'restaurant-brunch'),
		'flex' => __('Flex Layout', 'restaurant-brunch'),
	),
));