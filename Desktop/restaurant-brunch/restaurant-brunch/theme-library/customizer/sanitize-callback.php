<?php
function restaurant_brunch_sanitize_select( $restaurant_brunch_input, $restaurant_brunch_setting ) {
	$restaurant_brunch_input = sanitize_key( $restaurant_brunch_input );
	$restaurant_brunch_choices = $restaurant_brunch_setting->manager->get_control( $restaurant_brunch_setting->id )->choices;
	return ( array_key_exists( $restaurant_brunch_input, $restaurant_brunch_choices ) ? $restaurant_brunch_input : $restaurant_brunch_setting->default );
}

function restaurant_brunch_sanitize_switch( $restaurant_brunch_input ) {
	if ( true === $restaurant_brunch_input ) {
		return true;
	} else {
		return false;
	}
}

function restaurant_brunch_sanitize_google_fonts( $restaurant_brunch_input, $restaurant_brunch_setting ) {
	$restaurant_brunch_choices = $restaurant_brunch_setting->manager->get_control( $restaurant_brunch_setting->id )->choices;
	return ( array_key_exists( $restaurant_brunch_input, $restaurant_brunch_choices ) ? $restaurant_brunch_input : $restaurant_brunch_setting->default );
}
/**
 * Sanitize HTML input.
 *
 * @param string $restaurant_brunch_input HTML input to sanitize.
 * @return string Sanitized HTML.
 */
function restaurant_brunch_sanitize_html( $restaurant_brunch_input ) {
    return wp_kses_post( $restaurant_brunch_input );
}

/**
 * Sanitize URL input.
 *
 * @param string $restaurant_brunch_input URL input to sanitize.
 * @return string Sanitized URL.
 */
function restaurant_brunch_sanitize_url( $restaurant_brunch_input ) {
    return esc_url_raw( $restaurant_brunch_input );
}

// Sanitize Scroll Top Position
function restaurant_brunch_sanitize_scroll_top_position( $restaurant_brunch_input ) {
    $valid_positions = array( 'bottom-right', 'bottom-left', 'bottom-center' );
    if ( in_array( $restaurant_brunch_input, $valid_positions ) ) {
        return $restaurant_brunch_input;
    } else {
        return 'bottom-right'; // Default to bottom-right if invalid value
    }
}

function restaurant_brunch_sanitize_choices( $restaurant_brunch_input, $restaurant_brunch_setting ) {
	global $wp_customize; 
	$control = $wp_customize->get_control( $restaurant_brunch_setting->id ); 
	if ( array_key_exists( $restaurant_brunch_input, $control->choices ) ) {
		return $restaurant_brunch_input;
	} else {
		return $restaurant_brunch_setting->default;
	}
}

function restaurant_brunch_sanitize_range_value( $restaurant_brunch_number, $restaurant_brunch_setting ) {

	// Ensure input is an absolute integer.
	$restaurant_brunch_number = absint( $restaurant_brunch_number );

	// Get the input attributes associated with the setting.
	$restaurant_brunch_atts = $restaurant_brunch_setting->manager->get_control( $restaurant_brunch_setting->id )->input_attrs;

	// Get minimum number in the range.
	$restaurant_brunch_min = ( isset( $restaurant_brunch_atts['min'] ) ? $restaurant_brunch_atts['min'] : $restaurant_brunch_number );

	// Get maximum number in the range.
	$restaurant_brunch_max = ( isset( $restaurant_brunch_atts['max'] ) ? $restaurant_brunch_atts['max'] : $restaurant_brunch_number );

	// Get step.
	$restaurant_brunch_step = ( isset( $restaurant_brunch_atts['step'] ) ? $restaurant_brunch_atts['step'] : 1 );

	// If the number is within the valid range, return it; otherwise, return the default.
	return ( $restaurant_brunch_min <= $restaurant_brunch_number && $restaurant_brunch_number <= $restaurant_brunch_max && is_int( $restaurant_brunch_number / $restaurant_brunch_step ) ? $restaurant_brunch_number : $restaurant_brunch_setting->default );
}