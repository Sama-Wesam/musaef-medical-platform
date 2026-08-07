<?php
/**
 * Service Section
 *
 * @package restaurant_brunch
 */

 $wp_customize->add_section(
	'restaurant_brunch_service_section',
	array(
		'panel'    => 'restaurant_brunch_front_page_options',
		'title'    => esc_html__( 'Category Section', 'restaurant-brunch' ),
		'priority' => 10,
	)
);

//Service Section - Enable Section.
$wp_customize->add_setting(
	'restaurant_brunch_enable_service_section',
	array(
		'default'           => false,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_enable_service_section',
		array(
			'label'    => esc_html__( 'Enable Category Section', 'restaurant-brunch' ),
			'section'  => 'restaurant_brunch_service_section',
			'settings' => 'restaurant_brunch_enable_service_section'
		)
	)
);

// Service Section - Heading Label.
$wp_customize->add_setting(
	'restaurant_brunch_trending_post_heading',
	array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_trending_post_heading',
	array(
		'label'           => esc_html__( 'Heading', 'restaurant-brunch' ),
		'section'         => 'restaurant_brunch_service_section',
		'settings'        => 'restaurant_brunch_trending_post_heading',
		'type'            => 'text',
		'active_callback' => 'restaurant_brunch_is_service_section_enabled',
	)
);

// Partial Refresh
if ( isset( $wp_customize->selective_refresh ) ) {
	$wp_customize->selective_refresh->add_partial(
		'restaurant_brunch_enable_service_section',
		array(
			'selector' => '#restaurant_brunch_service_section .section-link',
			'settings' => 'restaurant_brunch_enable_service_section',
		)
	);
}