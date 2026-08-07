<?php
/**
 * Color Option
 *
 * @package restaurant_brunch
 */

// Primary Color.
$wp_customize->add_setting(
	'primary_color',
	array(
		'default'           => '#CBAB00',
		'sanitize_callback' => 'sanitize_hex_color',
	)
);

$wp_customize->add_control(
	new WP_Customize_Color_Control(
		$wp_customize,
		'primary_color',
		array(
			'label'    => __( 'Primary Color', 'restaurant-brunch' ),
			'section'  => 'colors',
			'priority' => 5,
		)
	)
);
