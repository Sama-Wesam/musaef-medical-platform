<?php
/**
 * Footer Options
 *
 * @package restaurant_brunch
 */

$wp_customize->add_section(
	'restaurant_brunch_footer_options',
	array(
		'panel' => 'restaurant_brunch_theme_options',
		'title' => esc_html__( 'Footer Options', 'restaurant-brunch' ),
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_footer_separators', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_footer_separators', array(
	'label' => __( 'Footer Settings', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_footer_options',
	'settings' => 'restaurant_brunch_footer_separators',
)));

// Footer Section - Enable Section.
$wp_customize->add_setting(
	'restaurant_brunch_enable_footer_section',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_footer_section',
		array(
			'label'    => esc_html__( 'Show / Hide Footer', 'restaurant-brunch' ),
			'section'  => 'restaurant_brunch_footer_options',
			'settings' => 'restaurant_brunch_enable_footer_section',
		)
	)
);

// column // 
$wp_customize->add_setting(
	'restaurant_brunch_footer_widget_column',
	array(
        'default'			=> '4',
		'capability'     	=> 'edit_theme_options',
		'sanitize_callback' => 'restaurant_brunch_sanitize_select',
		
	)
);	

$wp_customize->add_control(
	'restaurant_brunch_footer_widget_column',
	array(
	    'label'   		=> __('Select Widget Column','restaurant-brunch'),
		'description' => __('Note: Default footer widgets are shown. Add your preferred widgets in (Appearance > Widgets > Footer) to see changes.', 'restaurant-brunch'),
	    'section' 		=> 'restaurant_brunch_footer_options',
		'type'			=> 'select',
		'choices'        => 
		array(
			'' => __( 'None', 'restaurant-brunch' ),
			'1' => __( '1 Column', 'restaurant-brunch' ),
			'2' => __( '2 Column', 'restaurant-brunch' ),
			'3' => __( '3 Column', 'restaurant-brunch' ),
			'4' => __( '4 Column', 'restaurant-brunch' )
		) 
	) 
);

//  BG Color // 
$wp_customize->add_setting('footer_background_color_setting', array(
    'default' => '#000',
    'sanitize_callback' => 'sanitize_hex_color',
));

$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_background_color_setting', array(
    'label' => __('Footer Background Color', 'restaurant-brunch'),
    'section' => 'restaurant_brunch_footer_options',
)));

// Footer Background Image Setting
$wp_customize->add_setting('footer_background_image_setting', array(
    'default' => '',
    'sanitize_callback' => 'esc_url_raw',
));

$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_background_image_setting', array(
    'label' => __('Footer Background Image', 'restaurant-brunch'),
    'section' => 'restaurant_brunch_footer_options',
)));

// Footer Background Attachment
$wp_customize->add_setting(
	'restaurant_brunch_footer_image_attachment_setting',
	array(
		'default'=> 'scroll',
		'sanitize_callback' => 'restaurant_brunch_sanitize_choices'
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_image_attachment_setting',
	array(
		'type' => 'select',
		'label' => __('Footer Background Attatchment','restaurant-brunch'),
		'choices' => array(
			'fixed' => __('fixed','restaurant-brunch'),
			'scroll' => __('scroll','restaurant-brunch'),
		),
		'section'=> 'restaurant_brunch_footer_options',
  	)
);

//Footer Image Position
$wp_customize->add_setting(
	'restaurant_brunch_footer_img_position_setting',
	array(
        'default'			=> 'center center',
		'capability'     	=> 'edit_theme_options',
		'sanitize_callback' => 'restaurant_brunch_sanitize_choices',
		
	)
);	

$wp_customize->add_control(
	'restaurant_brunch_footer_img_position_setting',
	array(
		'label'   		=> __('Footer Image Position','restaurant-brunch'),
	    'section' 		=> 'restaurant_brunch_footer_options',
		'type'			=> 'select',
		'choices'       => 
		array(
			'center center'   => __( 'Center', 'restaurant-brunch' ),
			'center top'   	  => __( 'Top', 'restaurant-brunch' ),
			'center bottom'   => __( 'Bottom', 'restaurant-brunch' ),
		) 
	) 
);

$wp_customize->add_setting('footer_text_transform', array(
    'default' => 'none',
    'sanitize_callback' => 'sanitize_text_field',
));

// Add Footer Text Transform Control
$wp_customize->add_control('footer_text_transform', array(
    'label' => __('Footer Heading Text Transform', 'restaurant-brunch'),
    'section' => 'restaurant_brunch_footer_options',
    'settings' => 'footer_text_transform',
    'type' => 'select',
    'choices' => array(
        'none' => __('None', 'restaurant-brunch'),
        'capitalize' => __('Capitalize', 'restaurant-brunch'),
        'uppercase' => __('Uppercase', 'restaurant-brunch'),
        'lowercase' => __('Lowercase', 'restaurant-brunch'),
    ),
));

// Footer Heading Alignment
$wp_customize->add_setting(
	'restaurant_brunch_footer_header_align',
	array(
		'default' 			=> 'left',
		'sanitize_callback' => 'sanitize_text_field'
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_header_align',
	array(
		'label' => __('Footer Heading Alignment ','restaurant-brunch'),
		'section' => 'restaurant_brunch_footer_options',
		'type'			=> 'select',
		'choices' => 
		array(
			'left' => __('Left','restaurant-brunch'),
			'right' => __('Right','restaurant-brunch'),
			'center' => __('Center','restaurant-brunch'),
		),
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_copyright_separators', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_copyright_separators', array(
	'label' => __( 'Copyright Settings', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_footer_options',
	'settings' => 'restaurant_brunch_copyright_separators',
)));

// Copyright Section - Enable Section.
$wp_customize->add_setting(
	'restaurant_brunch_enable_copyright_section',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_copyright_section',
		array(
			'label'    => esc_html__( 'Show / Hide Copyright', 'restaurant-brunch' ),
			'section'  => 'restaurant_brunch_footer_options',
			'settings' => 'restaurant_brunch_enable_copyright_section',
		)
	)
);

$wp_customize->add_setting(
	'restaurant_brunch_footer_copyright_text',
	array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_copyright_text',
	array(
		'label'    => esc_html__( 'Copyright Text', 'restaurant-brunch' ),
		'section'  => 'restaurant_brunch_footer_options',
		'settings' => 'restaurant_brunch_footer_copyright_text',
		'type'     => 'textarea',
	)
);

//Copyright Alignment
$wp_customize->add_setting(
	'restaurant_brunch_footer_bottom_align',
	array(
		'default' 			=> 'center',
		'sanitize_callback' => 'sanitize_text_field'
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_bottom_align',
	array(
		'label' => __('Copyright Alignment ','restaurant-brunch'),
		'section' => 'restaurant_brunch_footer_options',
		'type'			=> 'select',
		'choices' => 
		array(
			'left' => __('Left','restaurant-brunch'),
			'right' => __('Right','restaurant-brunch'),
			'center' => __('Center','restaurant-brunch'),
		),
	)
);

//Copyright Font Size
$wp_customize->add_setting( 
	'restaurant_brunch_copyright_font_size', 
	array(
		'default'           => 16,
		'sanitize_callback' => 'absint',
	)
);

$wp_customize->add_control( 'restaurant_brunch_copyright_font_size', 
	array(
		'type'        => 'number',
		'section'     => 'restaurant_brunch_footer_options',
		'label'       => __( 'copyright Font Size ', 'restaurant-brunch' ),
		'input_attrs' => 
		array(
			'min'  => 10,
			'max'  => 100,
			'step' => 1,
		),
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_scroll_separators', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_scroll_separators', array(
	'label' => __( 'Scroll Top Settings', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_footer_options',
	'settings' => 'restaurant_brunch_scroll_separators',
)));

// Footer Options - Scroll Top.
$wp_customize->add_setting(
	'restaurant_brunch_scroll_top',
	array(
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
		'default'           => true,
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_scroll_top',
		array(
			'label'   => esc_html__( 'Enable Scroll Top Button', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_footer_options',
		)
	)
);
// icon // 
$wp_customize->add_setting(
	'restaurant_brunch_scroll_btn_icon',
	array(
        'default' => 'fas fa-chevron-up',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
		
	)
);	

$wp_customize->add_control(new Restaurant_Brunch_Change_Icon_Control($wp_customize, 
	'restaurant_brunch_scroll_btn_icon',
	array(
	    'label'   		=> __('Scroll Top Icon','restaurant-brunch'),
	    'section' 		=> 'restaurant_brunch_footer_options',
		'iconset' => 'fa',
	))  
);


$wp_customize->add_setting( 'restaurant_brunch_scroll_top_position', array(
    'default'           => 'bottom-right',
    'sanitize_callback' => 'restaurant_brunch_sanitize_scroll_top_position',
) );

// Add control for Scroll Top Button Position
$wp_customize->add_control( 'restaurant_brunch_scroll_top_position', array(
    'label'    => __( 'Scroll Top Button Position', 'restaurant-brunch' ),
    'section'  => 'restaurant_brunch_footer_options',
    'settings' => 'restaurant_brunch_scroll_top_position',
    'type'     => 'select',
    'choices'  => array(
        'bottom-right' => __( 'Bottom Right', 'restaurant-brunch' ),
        'bottom-left'  => __( 'Bottom Left', 'restaurant-brunch' ),
        'bottom-center'=> __( 'Bottom Center', 'restaurant-brunch' ),
    ),
) );

$wp_customize->add_setting( 'restaurant_brunch_scroll_top_shape', array(
    'default'           => 'box',
    'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( 'restaurant_brunch_scroll_top_shape', array(
    'label'    => __( 'Scroll to Top Button Shape', 'restaurant-brunch' ),
    'section'  => 'restaurant_brunch_footer_options',
    'settings' => 'restaurant_brunch_scroll_top_shape',
    'type'     => 'radio',
    'choices'  => array(
        'box'        => __( 'Box', 'restaurant-brunch' ),
        'curved-box' => __( 'Curved Box', 'restaurant-brunch' ),
        'circle'     => __( 'Circle', 'restaurant-brunch' ),
    ),
) );