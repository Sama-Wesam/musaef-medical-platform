<?php
/**
 * Footer Social Icons
 *
 * @package restaurant_brunch
 */

$wp_customize->add_section(
	'restaurant_brunch_footer_icon_options',
	array(
		'panel' => 'restaurant_brunch_theme_options',
		'title' => esc_html__( 'Footer Social Icons', 'restaurant-brunch' ),
	)
);

// Add Separator Custom Control
$wp_customize->add_setting( 'restaurant_brunch_footer_icon_separators', array(
	'sanitize_callback' => 'sanitize_text_field',
) );

$wp_customize->add_control( new Restaurant_Brunch_Separator_Custom_Control( $wp_customize, 'restaurant_brunch_footer_icon_separators', array(
	'label' => __( 'Footer Icon Settings', 'restaurant-brunch' ),
	'section' => 'restaurant_brunch_footer_icon_options',
	'settings' => 'restaurant_brunch_footer_icon_separators',
)));

// Footer Section - Enable Section.
$wp_customize->add_setting(
	'restaurant_brunch_enable_footer_icon_section',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_footer_icon_section',
		array(
			'label'    => esc_html__( 'Show / Hide Footer Icon', 'restaurant-brunch' ),
			'section'  => 'restaurant_brunch_footer_icon_options',
			'settings' => 'restaurant_brunch_enable_footer_icon_section',
		)
	)
);

// Add setting for Facebook Link
$wp_customize->add_setting(
	'restaurant_brunch_footer_facebook_link',
	array(
		'default'           => 'https://www.facebook.com/',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_facebook_link',
	array(
		'label'           => esc_html__( 'Facebook Link', 'restaurant-brunch'  ),
		'section'         => 'restaurant_brunch_footer_icon_options',
		'settings'        => 'restaurant_brunch_footer_facebook_link',
		'type'      => 'url'
	)
);

// Add setting for Facebook Icon Changing
$wp_customize->add_setting(
	'restaurant_brunch_facebook_icon',
	array(
        'default' => 'fab fa-facebook-f',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
		
	)
);	

$wp_customize->add_control(new Restaurant_Brunch_Change_Icon_Control($wp_customize, 
	'restaurant_brunch_facebook_icon',
	array(
	    'label'   		=> __('Facebook Icon','restaurant-brunch'),
	    'section' 		=> 'restaurant_brunch_footer_icon_options',
		'iconset' => 'fb',
	))  
);


// Add setting for Twitter Link
$wp_customize->add_setting(
	'restaurant_brunch_footer_twitter_link',
	array(
		'default'           => 'https://x.com/',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_twitter_link',
	array(
		'label'           => esc_html__( 'Twitter Link', 'restaurant-brunch'  ),
		'section'         => 'restaurant_brunch_footer_icon_options',
		'settings'        => 'restaurant_brunch_footer_twitter_link',
		'type'      => 'url'
	)
);

// Add setting for Twitter Icon Changing
$wp_customize->add_setting(
	'restaurant_brunch_twitter_icon',
	array(
        'default' => 'fab fa-twitter',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
		
	)
);	

$wp_customize->add_control(new Restaurant_Brunch_Change_Icon_Control($wp_customize, 
	'restaurant_brunch_twitter_icon',
	array(
	    'label'   		=> __('Twitter Icon','restaurant-brunch'),
	    'section' 		=> 'restaurant_brunch_footer_icon_options',
		'iconset' => 'fb',
	))  
);

// Add setting for Instagram Link
$wp_customize->add_setting(
	'restaurant_brunch_footer_instagram_link',
	array(
		'default'           => 'https://www.instagram.com/',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_instagram_link',
	array(
		'label'           => esc_html__( 'Instagram Link', 'restaurant-brunch'  ),
		'section'         => 'restaurant_brunch_footer_icon_options',
		'settings'        => 'restaurant_brunch_footer_instagram_link',
		'type'      => 'url'
	)
);

// Add setting for Instagram Icon Changing
$wp_customize->add_setting(
	'restaurant_brunch_instagram_icon',
	array(
        'default' => 'fab fa-instagram',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
		
	)
);	

$wp_customize->add_control(new Restaurant_Brunch_Change_Icon_Control($wp_customize, 
	'restaurant_brunch_instagram_icon',
	array(
	    'label'   		=> __('Instagram Icon','restaurant-brunch'),
	    'section' 		=> 'restaurant_brunch_footer_icon_options',
		'iconset' => 'fb',
	))  
);

// Add setting for Linkedin Link
$wp_customize->add_setting(
	'restaurant_brunch_footer_linkedin_link',
	array(
		'default'           => 'https://in.linkedin.com/',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_linkedin_link',
	array(
		'label'           => esc_html__( 'Linkedin Link', 'restaurant-brunch'  ),
		'section'         => 'restaurant_brunch_footer_icon_options',
		'settings'        => 'restaurant_brunch_footer_linkedin_link',
		'type'      => 'url'
	)
);

// Add setting for Linkedin Icon Changing
$wp_customize->add_setting(
	'restaurant_brunch_linkedin_icon',
	array(
        'default' => 'fab fa-linkedin',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
		
	)
);	

$wp_customize->add_control(new Restaurant_Brunch_Change_Icon_Control($wp_customize, 
	'restaurant_brunch_linkedin_icon',
	array(
	    'label'   		=> __('Linkedin Icon','restaurant-brunch'),
	    'section' 		=> 'restaurant_brunch_footer_icon_options',
		'iconset' => 'fb',
	))  
);

// Add setting for Youtube Link
$wp_customize->add_setting(
	'restaurant_brunch_footer_youtube_link',
	array(
		'default'           => 'https://www.youtube.com/',
		'sanitize_callback' => 'esc_url_raw',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_footer_youtube_link',
	array(
		'label'           => esc_html__( 'Youtube Link', 'restaurant-brunch'  ),
		'section'         => 'restaurant_brunch_footer_icon_options',
		'settings'        => 'restaurant_brunch_footer_youtube_link',
		'type'      => 'url'
	)
);

// Add setting for Youtube Icon Changing
$wp_customize->add_setting(
	'restaurant_brunch_youtube_icon',
	array(
        'default' => 'fab fa-youtube',
		'sanitize_callback' => 'sanitize_text_field',
		'capability' => 'edit_theme_options',
		
	)
);	

$wp_customize->add_control(new Restaurant_Brunch_Change_Icon_Control($wp_customize, 
	'restaurant_brunch_youtube_icon',
	array(
	    'label'   		=> __('Youtube Icon','restaurant-brunch'),
	    'section' 		=> 'restaurant_brunch_footer_icon_options',
		'iconset' => 'fb',
	))  
);