<?php
/**
 * Banner Section Settings
 *
 * @package restaurant_brunch
 */

// Add Banner Section
$wp_customize->add_section(
	'restaurant_brunch_banner_section',
	array(
		'panel'    => 'restaurant_brunch_front_page_options',
		'title'    => esc_html__( 'Banner Section', 'restaurant-brunch' ),
		'priority' => 10,
	)
);

// Enable Banner Section
$wp_customize->add_setting(
	'restaurant_brunch_enable_banner_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_banner_section',
		array(
			'label'    => esc_html__( 'Enable Banner Section', 'restaurant-brunch' ),
			'section'  => 'restaurant_brunch_banner_section',
			'settings' => 'restaurant_brunch_enable_banner_section',
		)
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_banner_separators', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_banner_separators', array(
	'label' => __( 'Select Banner Category', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_banner_section',
	'settings' => 'restaurant_brunch_banner_separators',
	'active_callback' => 'restaurant_brunch_is_banner_slider_section_enabled',
)));

// Banner Category Setting
$wp_customize->add_setting(
	'restaurant_brunch_banner_slider_category',
	array(
		'default'           => 'slider',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Customize_Category_Dropdown_Control(
		$wp_customize,
		'restaurant_brunch_banner_slider_category',
		array(
			'label'    => __( 'Select Banner Category', 'restaurant-brunch' ),
			'section'  => 'restaurant_brunch_banner_section',
			'settings' => 'restaurant_brunch_banner_slider_category',
			'active_callback' => 'restaurant_brunch_is_banner_slider_section_enabled',
		)
	)
);

// Button Label
$wp_customize->add_setting(
	'restaurant_brunch_banner_button_label',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_banner_button_label',
	array(
		'label'           => esc_html__( 'Button Label', 'restaurant-brunch'  ),
		'section'         => 'restaurant_brunch_banner_section',
		'settings'        => 'restaurant_brunch_banner_button_label',
		'type'            => 'text',
		'active_callback' => 'restaurant_brunch_is_banner_slider_section_enabled',
	)
);

// Button Link
$wp_customize->add_setting(
	'restaurant_brunch_banner_button_link',
	array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_banner_button_link',
	array(
		'label'           => esc_html__( 'Button Link', 'restaurant-brunch' ),
		'section'         => 'restaurant_brunch_banner_section',
		'settings'        => 'restaurant_brunch_banner_button_link',
		'type'            => 'url',
		'active_callback' => 'restaurant_brunch_is_banner_slider_section_enabled',
	)
);