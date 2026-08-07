<?php
/**
 * WooCommerce Settings
 *
 * @package restaurant_brunch
 */

$wp_customize->add_section(
	'restaurant_brunch_woocommerce_settings',
	array(
		'panel' => 'restaurant_brunch_theme_options',
		'title' => esc_html__( 'WooCommerce Settings', 'restaurant-brunch' ),
	)
);

//WooCommerce - Products per page.
$wp_customize->add_setting( 'restaurant_brunch_products_per_page', array(
    'default'           => 9,
    'sanitize_callback' => 'absint',
));

$wp_customize->add_control( 'restaurant_brunch_products_per_page', array(
    'type'        => 'number',
    'section'     => 'restaurant_brunch_woocommerce_settings',
    'label'       => __( 'Products Per Page', 'restaurant-brunch' ),
    'input_attrs' => array(
        'min'  => 0,
        'max'  => 50,
        'step' => 1,
    ),
));

//WooCommerce - Products per row.
$wp_customize->add_setting( 'restaurant_brunch_products_per_row', array(
    'default'           => '3',
    'sanitize_callback' => 'restaurant_brunch_sanitize_choices',
) );

$wp_customize->add_control( 'restaurant_brunch_products_per_row', array(
    'label'    => __( 'Products Per Row', 'restaurant-brunch' ),
    'section'  => 'restaurant_brunch_woocommerce_settings',
    'settings' => 'restaurant_brunch_products_per_row',
    'type'     => 'select',
    'choices'  => array(
        '2' => '2',
		'3' => '3',
		'4' => '4',
    ),
) );

//WooCommerce - Show / Hide Related Product.
$wp_customize->add_setting(
	'restaurant_brunch_related_product_show_hide',
	array(
		'default'           => true,
		'sanitize_callback' => 'restaurant_brunch_sanitize_switch',
	)
);

$wp_customize->add_control(
	new Restaurant_Brunch_Toggle_Switch_Custom_Control(
		$wp_customize,
		'restaurant_brunch_related_product_show_hide',
		array(
			'label'   => esc_html__( 'Show / Hide Related product', 'restaurant-brunch' ),
			'section' => 'restaurant_brunch_woocommerce_settings',
		)
	)
);

// WooCommerce - Product Sale Position.
$wp_customize->add_setting(
	'restaurant_brunch_product_sale_position', 
	array(
		'default' => 'left',
		'sanitize_callback' => 'sanitize_text_field',
));

$wp_customize->add_control(
	'restaurant_brunch_product_sale_position', 
	array(
		'label' => __('Product Sale Position', 'restaurant-brunch'),
		'section' => 'restaurant_brunch_woocommerce_settings',
		'settings' => 'restaurant_brunch_product_sale_position',
		'type' => 'radio',
		'choices' => 
	array(
		'left' => __('Left', 'restaurant-brunch'),
		'right' => __('Right', 'restaurant-brunch'),
	),
));