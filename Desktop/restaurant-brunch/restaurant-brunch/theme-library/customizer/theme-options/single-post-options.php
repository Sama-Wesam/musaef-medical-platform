<?php
/**
 * Single Post Options
 *
 * @package restaurant_brunch
 */

$wp_customize->add_section(
	'restaurant_brunch_single_post_options',
	array(
		'title' => esc_html__( 'Single Post Options', 'restaurant-brunch' ),
		'panel' => 'restaurant_brunch_theme_options',
	)
);

// Post Options - Show / Hide Date.
$wp_customize->add_setting(
	'restaurant_brunch_single_post_hide_date',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_single_post_hide_date',
		array(
			'label'   => esc_html__( 'Show / Hide Date', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_single_post_options',
		)
	)
);

// Post Options - Show / Hide Author.
$wp_customize->add_setting(
	'restaurant_brunch_single_post_hide_author',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_single_post_hide_author',
		array(
			'label'   => esc_html__( 'Show / Hide Author', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_single_post_options',
		)
	)
);

// Post Options - Show / Hide Comments.
$wp_customize->add_setting(
	'restaurant_brunch_single_post_hide_comments',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_single_post_hide_comments',
		array(
			'label'   => esc_html__( 'Show / Hide Comments', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_single_post_options',
		)
	)
);

// Post Options - Show / Hide Time.
$wp_customize->add_setting(
	'restaurant_brunch_single_post_hide_time',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_single_post_hide_time',
		array(
			'label'   => esc_html__( 'Show / Hide Time', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_single_post_options',
		)
	)
);

// Post Options - Show / Hide Category.
$wp_customize->add_setting(
	'restaurant_brunch_single_post_hide_category',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_single_post_hide_category',
		array(
			'label'   => esc_html__( 'Show / Hide Category', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_single_post_options',
		)
	)
);

// Post Options - Show / Hide Tag.
$wp_customize->add_setting(
	'restaurant_brunch_post_hide_tags',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_post_hide_tags',
		array(
			'label'   => esc_html__( 'Show / Hide Tag', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_single_post_options',
		)
	)
);

// Post Options - Comment Title.
$wp_customize->add_setting(
	'restaurant_brunch_blog_post_comment_title',
	array(
		'default'=> 'Leave a Reply',
		'sanitize_callback'	=> 'sanitize_text_field'
	)
);

$wp_customize->add_control(
	'restaurant_brunch_blog_post_comment_title',
	array(
		'label'	=> __('Comment Title','restaurant-brunch'),
		'input_attrs' => array(
			'placeholder' => __( 'Leave a Reply', 'restaurant-brunch' ),
		),
		'section'=> 'restaurant_brunch_single_post_options',
		'type'=> 'text'
	)
);