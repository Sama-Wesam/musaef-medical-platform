<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! restaurant_brunch_has_page_header() ) {
    return;
}

$restaurant_brunch_classes = array( 'page-header' );
$restaurant_brunch_style = restaurant_brunch_page_header_style();

if ( $restaurant_brunch_style ) {
    $restaurant_brunch_classes[] = $restaurant_brunch_style . '-page-header';
}

$restaurant_brunch_visibility = get_theme_mod( 'restaurant_brunch_page_header_visibility', 'all-devices' );

if ( 'hide-all-devices' === $restaurant_brunch_visibility ) {
    // Don't show the header at all
    return;
}

if ( 'hide-tablet' === $restaurant_brunch_visibility ) {
    $restaurant_brunch_classes[] = 'hide-on-tablet';
} elseif ( 'hide-mobile' === $restaurant_brunch_visibility ) {
    $restaurant_brunch_classes[] = 'hide-on-mobile';
} elseif ( 'hide-tablet-mobile' === $restaurant_brunch_visibility ) {
    $restaurant_brunch_classes[] = 'hide-on-tablet-mobile';
}

$restaurant_brunch_PAGE_TITLE_background_color = get_theme_mod('restaurant_brunch_page_title_background_color_setting', '');

// Get the toggle switch value
$restaurant_brunch_background_image_enabled = get_theme_mod('restaurant_brunch_page_header_style', true);

// Add background image to the header if enabled
$restaurant_brunch_background_image = get_theme_mod( 'restaurant_brunch_page_header_background_image', '' );
$restaurant_brunch_background_height = get_theme_mod( 'restaurant_brunch_page_header_image_height', '200' );
$restaurant_brunch_inline_style = '';

if ( $restaurant_brunch_background_image_enabled && ! empty( $restaurant_brunch_background_image ) ) {
    $restaurant_brunch_inline_style .= 'background-image: url(' . esc_url( $restaurant_brunch_background_image ) . '); ';
    $restaurant_brunch_inline_style .= 'height: ' . esc_attr( $restaurant_brunch_background_height ) . 'px; ';
    $restaurant_brunch_inline_style .= 'background-size: cover; ';
    $restaurant_brunch_inline_style .= 'background-position: center center; ';

    // Add the unique class if the background image is set
    $restaurant_brunch_classes[] = 'has-background-image';
}

$restaurant_brunch_classes = implode( ' ', $restaurant_brunch_classes );
$restaurant_brunch_heading = get_theme_mod( 'restaurant_brunch_page_header_heading_tag', 'h1' );
$restaurant_brunch_heading = apply_filters( 'restaurant_brunch_page_header_heading', $restaurant_brunch_heading );

?>

<?php do_action( 'restaurant_brunch_before_page_header' ); ?>

<header class="<?php echo esc_attr( $restaurant_brunch_classes ); ?>" style="<?php echo esc_attr( $restaurant_brunch_inline_style ); ?> background-color: <?php echo esc_attr($restaurant_brunch_PAGE_TITLE_background_color); ?>;">

    <?php do_action( 'restaurant_brunch_before_page_header_inner' ); ?>

    <div class="asterthemes-wrapper page-header-inner">

        <?php if ( restaurant_brunch_has_page_header() ) : ?>

            <<?php echo esc_attr( $restaurant_brunch_heading ); ?> class="page-header-title">
                <?php echo wp_kses_post( restaurant_brunch_get_page_title() ); ?>
            </<?php echo esc_attr( $restaurant_brunch_heading ); ?>>

        <?php endif; ?>

        <?php if ( function_exists( 'restaurant_brunch_breadcrumb' ) ) : ?>
            <?php restaurant_brunch_breadcrumb(); ?>
        <?php endif; ?>

    </div><!-- .page-header-inner -->

    <?php do_action( 'restaurant_brunch_after_page_header_inner' ); ?>

</header><!-- .page-header -->

<?php do_action( 'restaurant_brunch_after_page_header' ); ?>