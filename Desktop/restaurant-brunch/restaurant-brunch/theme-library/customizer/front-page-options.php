<?php
/**
 * Front Page Options
 *
 * @package Restaurant Brunch
 */

$wp_customize->add_panel(
	'restaurant_brunch_front_page_options',
	array(
		'title'    => esc_html__( 'Front Page Options', 'restaurant-brunch' ),
		'priority' => 20,
	)
);

// Header Section.
require get_template_directory() . '/theme-library/customizer/front-page-options/header.php';

// Banner Section.
require get_template_directory() . '/theme-library/customizer/front-page-options/banner.php';

// Tranding Product Section.
require get_template_directory() . '/theme-library/customizer/front-page-options/services.php';