<?php
/**
 * Active Callbacks
 *
 * @package restaurant_brunch
 */

// Theme Options.
function restaurant_brunch_is_pagination_enabled( $restaurant_brunch_control ) {
	return ( $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_enable_pagination' )->value() );
}
function restaurant_brunch_is_breadcrumb_enabled( $restaurant_brunch_control ) {
	return ( $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_enable_breadcrumb' )->value() );
}
function restaurant_brunch_is_layout_enabled( $restaurant_brunch_control ) {
	return ( $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_website_layout' )->value() );
}
function restaurant_brunch_is_pagetitle_bcakground_image_enabled( $restaurant_brunch_control ) {
	return ( $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_page_header_style' )->value() );
}
function restaurant_brunch_is_preloader_style( $restaurant_brunch_control ) {
	return ( $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_enable_preloader' )->value() );
}

// Header Options.
function restaurant_brunch_is_topbar_enabled( $restaurant_brunch_control ) {
	return ( $restaurant_brunch_control->manager->get_Setting( 'restaurant_brunch_enable_topbar' )->value() );
}

// Banner Slider Section.
function restaurant_brunch_is_banner_slider_section_enabled( $restaurant_brunch_control ) {
	return ( $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_enable_banner_section' )->value() );
}
function restaurant_brunch_is_banner_slider_section_and_content_type_post_enabled( $restaurant_brunch_control ) {
	$restaurant_brunch_content_type = $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_banner_slider_content_type' )->value();
	return ( restaurant_brunch_is_banner_slider_section_enabled( $restaurant_brunch_control ) && ( 'post' === $restaurant_brunch_content_type ) );
}
function restaurant_brunch_is_banner_slider_section_and_content_type_page_enabled( $restaurant_brunch_control ) {
	$restaurant_brunch_content_type = $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_banner_slider_content_type' )->value();
	return ( restaurant_brunch_is_banner_slider_section_enabled( $restaurant_brunch_control ) && ( 'page' === $restaurant_brunch_content_type ) );
}

// Service section.
function restaurant_brunch_is_post_tab_section_and_content_type_page_enabled( $restaurant_brunch_control ) {
	$restaurant_brunch_content_type = $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_banner_slider_content_type' )->value();
	return ( restaurant_brunch_is_banner_slider_section_enabled( $restaurant_brunch_control ) && ( 'page' === $restaurant_brunch_content_type ) );
}
function restaurant_brunch_is_post_tab_section_and_content_type_post_enabled( $restaurant_brunch_control ) {
	$restaurant_brunch_content_type = $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_banner_slider_content_type' )->value();
	return ( restaurant_brunch_is_banner_slider_section_enabled( $restaurant_brunch_control ) && ( 'post' === $restaurant_brunch_content_type ) );
}
function restaurant_brunch_is_service_section_enabled( $restaurant_brunch_control ) {
	return ( $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_enable_service_section' )->value() );
}
function restaurant_brunch_is_service_section_and_content_type_post_enabled( $restaurant_brunch_control ) {
	$restaurant_brunch_content_type = $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_service_content_type' )->value();
	return ( restaurant_brunch_is_service_section_enabled( $restaurant_brunch_control ) && ( 'post' === $restaurant_brunch_content_type ) );
}
function restaurant_brunch_is_service_section_and_content_type_page_enabled( $restaurant_brunch_control ) {
	$restaurant_brunch_content_type = $restaurant_brunch_control->manager->get_setting( 'restaurant_brunch_service_content_type' )->value();
	return ( restaurant_brunch_is_service_section_enabled( $restaurant_brunch_control ) && ( 'page' === $restaurant_brunch_content_type ) );
}