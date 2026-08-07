<?php
/**
 * Header Section Settings
 *
 * @package restaurant_brunch
 */

// ---------------------------------------- HEADER OPTIONS ----------------------------------------------------

$wp_customize->add_section(
	'restaurant_brunch_header_options',
	array(
		'panel' => 'restaurant_brunch_front_page_options',
		'title' => esc_html__( 'Header Options', 'restaurant-brunch' ),
        'priority' => 1,
	)
);


// Add setting for sticky header
$wp_customize->add_setting(
	'restaurant_brunch_enable_sticky_header',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
		'default'           => false,
	)
);

// Add control for sticky header setting
$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_sticky_header',
		array(
			'label'   => esc_html__( 'Enable Sticky Header', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_header_options',
		)
	)
);

// Enable Banner Section
$wp_customize->add_setting(
	'restaurant_brunch_enable_topbar',
	array(
		'default'           => false,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_topbar',
		array(
			'label'    => esc_html__( 'Enable Topbar', 'restaurant-brunch' ),
			'section'  => 'restaurant_brunch_header_options',
			'settings' => 'restaurant_brunch_enable_topbar',
		)
	)
);

// Add setting for sticky header
$wp_customize->add_setting(
	'restaurant_brunch_enable_header_search_section',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
		'default'           => false,
	)
);

// Add control for sticky header setting
$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_header_search_section',
		array(
			'label'   => esc_html__( 'Enable Search', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_header_options',
		)
	)
);

// Header Options 
$wp_customize->add_setting(
	'restaurant_brunch_discount_topbar_text',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_discount_topbar_text',
	array(
		'label'           => esc_html__( 'Topbar Discount Text', 'restaurant-brunch' ),
		'section'         => 'restaurant_brunch_header_options',
		'type'            => 'text',
		'active_callback' => 'restaurant_brunch_is_topbar_enabled',
	)
);

// Header Options - Topbar Button Text.
$wp_customize->add_setting(
	'restaurant_brunch_discount_topbar_button_text',
	array(
		'default'           => 'Subscribe',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_discount_topbar_button_text',
	array(
		'label'           => esc_html__( 'Discount Button Text', 'restaurant-brunch' ),
		'section'         => 'restaurant_brunch_header_options',
		'type'            => 'text',
		'active_callback' => 'restaurant_brunch_is_topbar_enabled',
	)
);

// Header Options - Enable Social Icons.
$wp_customize->add_setting(
	'restaurant_brunch_enable_social',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
		'default'           => true,
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_social',
		array(
			'label'   => esc_html__( 'Enable Social', 'restaurant-brunch' ),
			'description' => esc_html__( 'If you want to add a social icon you need to go to Dashboard = Appearance = Menus then create a new menu now add Custom Links then add proper links then choose Social then click Create Menu.', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_header_options',
			'active_callback' => 'restaurant_brunch_is_topbar_enabled',
		)
	)
);

// Contact Section - Phone Icon.
$wp_customize->add_setting(
    'restaurant_brunch_header_phone_icon',
    array(
        'default' => 'fas fa-phone-alt', // Set default icon here
        'sanitize_callback' => 'sanitize_text_field',
        'capability' => 'edit_theme_options',
    )
);

$wp_customize->add_control(new Restaurant_Brunch_Change_Icon_Control(
    $wp_customize, 
    'restaurant_brunch_header_phone_icon',
    array(
        'label'    => __('Phone Icon','restaurant-brunch'),
        'section'  => 'restaurant_brunch_header_options',
        'iconset'  => 'fa',
    )
));

// Contact Section - Phone Number.
$wp_customize->add_setting(
	'restaurant_brunch_phone_number',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_phone_number',
	array(
		'label'           => esc_html__( 'Phone Number', 'restaurant-brunch' ),
		'section'         => 'restaurant_brunch_header_options',
		'settings'        => 'restaurant_brunch_phone_number',
		'type'            => 'text',
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_menu_separator', array(
	'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_menu_separator', array(
	'label' => __( 'Menu Settings', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_header_options',
	'settings' => 'restaurant_brunch_menu_separator',
)));

$wp_customize->add_setting( 'restaurant_brunch_menu_font_size', array(
    'default'           => 14,
    'sanitize_callback' => 'absint',
));

// Add control for site title size
$wp_customize->add_control( 'restaurant_brunch_menu_font_size', array(
    'type'        => 'number',
    'section'     => 'restaurant_brunch_header_options',
    'label'       => __( 'Menu Font Size ', 'restaurant-brunch' ),
    'input_attrs' => array(
        'min'  => 10,
        'max'  => 100,
        'step' => 1,
    ),
));

// Add setting for menu font weight
$wp_customize->add_setting('restaurant_brunch_menu_font_weight', array(
    'default'           => '400',
    'sanitize_callback' => 'sanitize_text_field',
));

// Add control for menu font weight
$wp_customize->add_control('restaurant_brunch_menu_font_weight', array(
    'type'     => 'select',
    'section'  => 'restaurant_brunch_header_options', 
    'label'    => __('Menu Font Weight', 'restaurant-brunch'),
    'choices'  => array(
		'100' => __('100','restaurant-brunch'),
		'200' => __('200','restaurant-brunch'),
		'300' => __('300','restaurant-brunch'),
		'400' => __('400','restaurant-brunch'),
		'500' => __('500','restaurant-brunch'),
		'600' => __('600','restaurant-brunch'),
		'700' => __('700','restaurant-brunch'),
		'800' => __('800','restaurant-brunch'),
		'900' => __('900','restaurant-brunch'),
    ),
));

$wp_customize->add_setting( 'restaurant_brunch_menu_text_transform', array(
    'default'           => 'uppercase', // Default value for text transform
    'sanitize_callback' => 'sanitize_text_field',
) );

// Add control for menu text transform
$wp_customize->add_control( 'restaurant_brunch_menu_text_transform', array(
    'type'     => 'select',
    'section'  => 'restaurant_brunch_header_options', // Adjust the section as needed
    'label'    => __( 'Menu Text Transform', 'restaurant-brunch' ),
    'choices'  => array(
        'none'       => __( 'None', 'restaurant-brunch' ),
        'capitalize' => __( 'Capitalize', 'restaurant-brunch' ),
        'uppercase'  => __( 'Uppercase', 'restaurant-brunch' ),
        'lowercase'  => __( 'Lowercase', 'restaurant-brunch' ),
    ),
) );

// Menu Text Color 
$wp_customize->add_setting(
	'restaurant_brunch_menu_text_color', 
	array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, 
		'restaurant_brunch_menu_text_color', 
		array(
			'label' => __('Menu Color', 'restaurant-brunch'),
			'section' => 'restaurant_brunch_header_options',
		)
	)
);

// Sub Menu Text Color 
$wp_customize->add_setting(
	'restaurant_brunch_sub_menu_text_color', 
	array(
		'default' => '',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize, 
		'restaurant_brunch_sub_menu_text_color', 
		array(
			'label' => __('Sub Menu Color', 'restaurant-brunch'),
			'section' => 'restaurant_brunch_header_options',
		)
	)
);
