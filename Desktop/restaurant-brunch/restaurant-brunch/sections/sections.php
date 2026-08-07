<?php
/**
 * Render homepage sections.
 */
function restaurant_brunch_homepage_sections() {
	$restaurant_brunch_homepage_sections = array_keys( restaurant_brunch_get_homepage_sections() );

	foreach ( $restaurant_brunch_homepage_sections as $restaurant_brunch_section ) {
		require get_template_directory() . '/sections/' . $restaurant_brunch_section . '.php';
	}
}