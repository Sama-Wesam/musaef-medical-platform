<?php
/**
 * Excerpt
 *
 * @package restaurant_brunch
 */

$wp_customize->add_section(
	'restaurant_brunch_excerpt_options',
	array(
		'panel' => 'restaurant_brunch_theme_options',
		'title' => esc_html__( 'Excerpt', 'restaurant-brunch' ),
	)
);

// Excerpt - Excerpt Length.
$wp_customize->add_setting(
	'restaurant_brunch_excerpt_length',
	array(
		'default'           => 20,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	)
);

$wp_customize->add_control(
	'restaurant_brunch_excerpt_length',
	array(
		'label'       => esc_html__( 'Excerpt Length (no. of words)', 'restaurant-brunch' ),
		'section'     => 'restaurant_brunch_excerpt_options',
		'settings'    => 'restaurant_brunch_excerpt_length',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 10,
			'max'  => 200,
			'step' => 1,
		),
	)
);