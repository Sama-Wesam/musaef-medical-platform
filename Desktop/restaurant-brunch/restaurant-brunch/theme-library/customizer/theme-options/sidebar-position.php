<?php
/**
 * Sidebar Position
 *
 * @package restaurant_brunch
 */

$wp_customize->add_section(
	'restaurant_brunch_sidebar_position',
	array(
		'title' => esc_html__( 'Sidebar Position', 'restaurant-brunch' ),
		'panel' => 'restaurant_brunch_theme_options',
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_global_sidebar_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_global_sidebar_separator', array(
	'label' => __( 'Global Sidebar Position', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_sidebar_position',
	'settings' => 'restaurant_brunch_global_sidebar_separator',
)));

// Sidebar Position - Global Sidebar Position.
$wp_customize->add_setting(
	'restaurant_brunch_sidebar_position',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_sidebar_position',
	array(
		'label'   => esc_html__( 'Select Sidebar Position', 'restaurant-brunch' ),
		'section' => 'restaurant_brunch_sidebar_position',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'restaurant-brunch' ),
			'left-sidebar'  => esc_html__( 'Left Sidebar', 'restaurant-brunch' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'restaurant-brunch' ),
		),
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_page_sidebar_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_page_sidebar_separator', array(
	'label' => __( 'Page Sidebar Position', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_sidebar_position',
	'settings' => 'restaurant_brunch_page_sidebar_separator',
)));

// Sidebar Position - Page Sidebar Position.
$wp_customize->add_setting(
	'restaurant_brunch_page_sidebar_position',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_page_sidebar_position',
	array(
		'label'   => esc_html__( 'Select Sidebar Position', 'restaurant-brunch' ),
		'section' => 'restaurant_brunch_sidebar_position',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'restaurant-brunch' ),
			'left-sidebar'  => esc_html__( 'Left Sidebar', 'restaurant-brunch' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'restaurant-brunch' ),
		),
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_post_sidebar_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_post_sidebar_separator', array(
	'label' => __( 'Post Sidebar Position', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_sidebar_position',
	'settings' => 'restaurant_brunch_post_sidebar_separator',
)));

// Sidebar Position - Post Sidebar Position.
$wp_customize->add_setting(
	'restaurant_brunch_post_sidebar_position',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_select',
		'default'           => 'right-sidebar',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_post_sidebar_position',
	array(
		'label'   => esc_html__( 'Select Sidebar Position', 'restaurant-brunch' ),
		'section' => 'restaurant_brunch_sidebar_position',
		'type'    => 'select',
		'choices' => array(
			'right-sidebar' => esc_html__( 'Right Sidebar', 'restaurant-brunch' ),
			'left-sidebar'  => esc_html__( 'Left Sidebar', 'restaurant-brunch' ),
			'no-sidebar'    => esc_html__( 'No Sidebar', 'restaurant-brunch' ),
		),
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_sidebar_width_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_sidebar_width_separator', array(
	'label' => __( 'Sidebar Width Setting', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_sidebar_position',
	'settings' => 'restaurant_brunch_sidebar_width_separator',
)));


$wp_customize->add_setting( 'restaurant_brunch_sidebar_width', array(
	'default'           => '30',
	'sanitize_callback' => 'restaurant_brunch_sanitize_range_value',
) );

$wp_customize->add_control(new Restaurant_Brunch_Customize_Range_Control($wp_customize, 'restaurant_brunch_sidebar_width', array(
	'section'     => 'restaurant_brunch_sidebar_position',
	'label'       => __( 'Adjust Sidebar Width', 'restaurant-brunch' ),
	'description' => __( 'Adjust the width of the sidebar.', 'restaurant-brunch' ),
	'input_attrs' => array(
		'min'  => 10,
		'max'  => 50,
		'step' => 1,
	),
)));

$wp_customize->add_setting( 'restaurant_brunch_sidebar_widget_font_size', array(
    'default'           => 24,
    'sanitize_callback' => 'absint',
) );

// Add control for site title size
$wp_customize->add_control( 'restaurant_brunch_sidebar_widget_font_size', array(
    'type'        => 'number',
    'section'     => 'restaurant_brunch_sidebar_position',
    'label'       => __( 'Sidebar Widgets Heading Font Size ', 'restaurant-brunch' ),
    'input_attrs' => array(
        'min'  => 10,
        'max'  => 100,
        'step' => 1,
    ),
));