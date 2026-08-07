<?php
/**
 * Header Options
 *
 * @package restaurant_brunch
 */

// ---------------------------------------- GENERAL OPTIONBS ----------------------------------------------------
// ---------------------------------------- PRELOADER ----------------------------------------------------

$wp_customize->add_section(
	'restaurant_brunch_general_options',
	array(
		'panel' => 'restaurant_brunch_theme_options',
		'title' => esc_html__( 'General Options', 'restaurant-brunch' ),
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_preloader_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_preloader_separator', array(
	'label' => __( 'Enable / Disable Site Preloader Section', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_general_options',
	'settings' => 'restaurant_brunch_preloader_separator',
) ) );


// General Options - Enable Preloader.
$wp_customize->add_setting(
	'restaurant_brunch_enable_preloader',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
		'default'           => false,
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_preloader',
		array(
			'label'   => esc_html__( 'Enable Preloader', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_general_options',
		)
	)
);

// Preloader Style Setting
$wp_customize->add_setting(
	'restaurant_brunch_preloader_style',
	array(
		'default'           => 'style1',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_preloader_style',
	array(
		'type'     => 'select',
		'label'    => esc_html__('Select Preloader Styles', 'restaurant-brunch'),
		'active_callback' => 'restaurant_brunch_is_preloader_style',
		'section'  => 'restaurant_brunch_general_options',
		'choices'  => array(
			'style1' => esc_html__('Style 1', 'restaurant-brunch'),
			'style2' => esc_html__('Style 2', 'restaurant-brunch'),
			'style3' => esc_html__('Style 3', 'restaurant-brunch'),
		),
	)
);

// Preloader Background Color Setting
$wp_customize->add_setting(
	'restaurant_brunch_preloader_background_color_setting',
	 array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, 'restaurant_brunch_preloader_background_color_setting', 
		array(
			'label' => __('Preloader Background Color', 'restaurant-brunch'),
			'active_callback' => 'restaurant_brunch_is_preloader_style',
			'section' => 'restaurant_brunch_general_options',
		)
	)
);

// Preloader Background Image Setting
$wp_customize->add_setting(
	'restaurant_brunch_preloader_background_image_setting', 
	array(
		'default' => '',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	new WP_Customize_Image_Control(
		$wp_customize, 'restaurant_brunch_preloader_background_image_setting',
		 array(
			'label' => __('Preloader Background Image', 'restaurant-brunch'),
			'active_callback' => 'restaurant_brunch_is_preloader_style',
			'section' => 'restaurant_brunch_general_options',
		)
	)
);

// ---------------------------------------- Website layout ----------------------------------------------------


// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_layuout_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_layuout_separator', array(
	'label' => __( 'Website Layout Setting', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_general_options',
	'settings' => 'restaurant_brunch_layuout_separator',
)));


$wp_customize->add_setting(
	'restaurant_brunch_website_layout',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
		'default'           => false,
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_website_layout',
		array(
			'label'   => esc_html__('Boxed Layout', 'restaurant-brunch'),
			'section' => 'restaurant_brunch_general_options',
		)
	)
);

$wp_customize->add_setting('restaurant_brunch_layout_width_margin', array(
	'default'           => 50,
	'sanitize_callback' => 'restaurant_brunch_sanitize_range_value',
));

$wp_customize->add_control(new Restaurant_Brunch_Customize_Range_Control($wp_customize, 'restaurant_brunch_layout_width_margin', array(
		'label'       => __('Set Width', 'restaurant-brunch'),
		'description' => __('Adjust the width around the website layout by moving the slider. Use this setting to customize the appearance of your site to fit your design preferences.', 'restaurant-brunch'),
		'section'     => 'restaurant_brunch_general_options',
		'settings'    => 'restaurant_brunch_layout_width_margin',
		'active_callback' => 'restaurant_brunch_is_layout_enabled',
		'input_attrs' => array(
			'min'  => 0,
			'max'  => 130,
			'step' => 1,
		),
)));

// ---------------------------------------- BREADCRUMB ----------------------------------------------------

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_breadcrumb_separators', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_breadcrumb_separators', array(
	'label' => __( 'Enable / Disable Breadcrumb Section', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_general_options',
	'settings' => 'restaurant_brunch_breadcrumb_separators',
)));

// Breadcrumb - Enable Breadcrumb.
$wp_customize->add_setting(
	'restaurant_brunch_enable_breadcrumb',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
		'default'           => true,
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_breadcrumb',
		array(
			'label'   => esc_html__( 'Enable Breadcrumb', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_general_options',
		)
	)
);

// Breadcrumb - Separator.
$wp_customize->add_setting(
	'restaurant_brunch_breadcrumb_separator',
	array(
		'sanitize_callback' => 'sanitize_text_field',
		'default'           => '/',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_breadcrumb_separator',
	array(
		'label'           => esc_html__( 'Separator', 'restaurant-brunch' ),
		'active_callback' => 'restaurant_brunch_is_breadcrumb_enabled',
		'section'         => 'restaurant_brunch_general_options',
	)
);

// ----------------------------------------SITE IDENTITY----------------------------------------------------

// Site Logo - Enable Setting.
$wp_customize->add_setting(
	'restaurant_brunch_enable_site_logo',
	array(
		'default'           => true, // Default is to display the logo.
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch', // Sanitize using a custom switch function.
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_site_logo',
		array(
			'label'    => esc_html__( 'Enable Site Logo', 'restaurant-brunch' ),
			'section'  => 'title_tagline', // Section to add this control.
			'settings' => 'restaurant_brunch_enable_site_logo',
		)
	)
);

// Site Title - Enable Setting.
$wp_customize->add_setting(
	'restaurant_brunch_enable_site_title_setting',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_site_title_setting',
		array(
			'label'    => esc_html__( 'Enable Site Title', 'restaurant-brunch' ),
			'section'  => 'title_tagline',
			'settings' => 'restaurant_brunch_enable_site_title_setting',
		)
	)
);

// Tagline - Enable Setting.
$wp_customize->add_setting(
	'restaurant_brunch_enable_tagline_setting',
	array(
		'default'           => false,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_tagline_setting',
		array(
			'label'    => esc_html__( 'Enable Tagline', 'restaurant-brunch' ),
			'section'  => 'title_tagline',
			'settings' => 'restaurant_brunch_enable_tagline_setting',
		)
	)
);

$wp_customize->add_setting( 'restaurant_brunch_site_title_size', array(
    'default'           => 25, // Default font size in pixels
    'sanitize_callback' => 'absint', // Sanitize the input as a positive integer
) );

// Add control for site title size
$wp_customize->add_control( 'restaurant_brunch_site_title_size', array(
    'type'        => 'number',
    'section'     => 'title_tagline', // You can change this section to your preferred section
    'label'       => __( 'Site Title Font Size ', 'restaurant-brunch' ),
    'input_attrs' => array(
        'min'  => 10,
        'max'  => 100,
        'step' => 1,
    ),
) );

$wp_customize->add_setting('restaurant_brunch_site_logo_width', array(
    'default'           => 150,
    'sanitize_callback' => 'restaurant_brunch_sanitize_range_value',
));

$wp_customize->add_control(new Restaurant_Brunch_Customize_Range_Control($wp_customize, 'restaurant_brunch_site_logo_width', array(
    'label'       => __('Adjust Site Logo Width', 'restaurant-brunch'),
    'description' => __('This setting controls the Width of Site Logo', 'restaurant-brunch'),
    'section'     => 'title_tagline',
    'settings'    => 'restaurant_brunch_site_logo_width',
    'input_attrs' => array(
        'min'  => 0,
        'max'  => 400,
        'step' => 5,
    ),
)));