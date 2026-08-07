<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package restaurant_brunch
 */


// Output inline CSS based on Customizer setting

// Breadcrumb - Enable Breadcrumb.
function restaurant_brunch_customizer_css_inline() {
	$restaurant_brunch_custom_css = '';
	$restaurant_brunch_enable_breadcrumb = get_theme_mod( 'restaurant_brunch_enable_breadcrumb', true );

	if ( ! $restaurant_brunch_enable_breadcrumb ) {
		$restaurant_brunch_custom_css .= 'nav.woocommerce-breadcrumb { 
            display: none; 
        }';
	}

	if ( ! empty( $restaurant_brunch_custom_css ) ) {
		wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_customizer_css_inline' );

function restaurant_brunch_custom_css() {
    $restaurant_brunch_slider = get_theme_mod('restaurant_brunch_enable_banner_section', false);
    ?>
    <style type="text/css">
        .home .bottom-header-outer-wrapper {
            z-index: 99;
            width: 100%;
            <?php if ( $restaurant_brunch_slider ) : ?>
                background: transparent;
                position: absolute;
            <?php else : ?>
                background: var(--primary-color);
                position: static;  
                padding: 10px 0;     
            <?php endif; ?>
        }
    </style>
    <?php
}
add_action( 'wp_head', 'restaurant_brunch_custom_css' );

// Hook the function into the wp_head action
add_action('wp_head', 'restaurant_brunch_custom_css');

//Global Color
function restaurant_brunch_customize_css() {
	$restaurant_brunch_primary_color = get_theme_mod( 'primary_color', '#CBAB00' );
	$restaurant_brunch_custom_css = ":root { --primary-color: {$restaurant_brunch_primary_color}; }";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_customize_css' );

//Google Fonts
function restaurant_brunch_enqueue_selected_fonts() {
    $restaurant_brunch_fonts_url = restaurant_brunch_get_fonts_url();
    if (!empty($restaurant_brunch_fonts_url)) {
        wp_enqueue_style('restaurant-brunch-google-fonts', $restaurant_brunch_fonts_url, array(), null);
    }
}
add_action('wp_enqueue_scripts', 'restaurant_brunch_enqueue_selected_fonts');

//Set Width
function restaurant_brunch_layout_customizer_css() {
	$restaurant_brunch_margin = get_theme_mod( 'restaurant_brunch_layout_width_margin', 50 );
	$restaurant_brunch_custom_css = "body.site-boxed--layout #page { margin: 0 {$restaurant_brunch_margin}px; }";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_layout_customizer_css', 20 );

//Blog Content Alignment
function restaurant_brunch_blog_layout_customizer_css() {
	// Retrieve the blog layout option
	$restaurant_brunch_layout_option = get_theme_mod( 'restaurant_brunch_blog_layout_option_setting', 'Left' );

	// Determine text alignment based on option
	switch ( $restaurant_brunch_layout_option ) {
		case 'Right':
			$restaurant_brunch_text_align = 'right';
			break;
		case 'Default':
			$restaurant_brunch_text_align = 'center';
			break;
		case 'Left':
		default:
			$restaurant_brunch_text_align = 'left';
			break;
	}

	// Create custom CSS
	$restaurant_brunch_custom_css = ".mag-post-detail { text-align: {$restaurant_brunch_text_align}; }";

	// Attach CSS to theme style
	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_blog_layout_customizer_css' );

//Sidebar Width Setting
function restaurant_brunch_sidebar_width_customizer_css() {
	$restaurant_brunch_sidebar_width = get_theme_mod( 'restaurant_brunch_sidebar_width', '30' );

	$restaurant_brunch_custom_css = "
		.right-sidebar .asterthemes-wrapper .asterthemes-page {
			grid-template-columns: auto {$restaurant_brunch_sidebar_width}%;
		}
		.left-sidebar .asterthemes-wrapper .asterthemes-page {
			grid-template-columns: {$restaurant_brunch_sidebar_width}% auto;
		}
	";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_sidebar_width_customizer_css' );


if ( ! function_exists( 'restaurant_brunch_get_page_title' ) ) {
    function restaurant_brunch_get_page_title() {
        $restaurant_brunch_title = '';

        if (is_404()) {
            $restaurant_brunch_title = esc_html__('Page Not Found', 'restaurant-brunch');
        } elseif (is_search()) {
            $restaurant_brunch_title = esc_html__('Search Results for: ', 'restaurant-brunch') . esc_html(get_search_query());
        } elseif (is_home() && !is_front_page()) {
            $restaurant_brunch_title = esc_html__('Blogs', 'restaurant-brunch');
        } elseif (function_exists('is_shop') && is_shop()) {
            $restaurant_brunch_title = esc_html__('Shop', 'restaurant-brunch');
        } elseif (is_page()) {
            $restaurant_brunch_title = get_the_title();
        } elseif (is_single()) {
            $restaurant_brunch_title = get_the_title();
        } elseif (is_archive()) {
            $restaurant_brunch_title = get_the_archive_title();
        } else {
            $restaurant_brunch_title = get_the_archive_title();
        }

        return apply_filters('restaurant_brunch_page_title', $restaurant_brunch_title);
    }
}

if ( ! function_exists( 'restaurant_brunch_has_page_header' ) ) {
    function restaurant_brunch_has_page_header() {
        // Default to true (display header)
        $restaurant_brunch_return = true;

        // Custom conditions for disabling the header
        if ('hide-all-devices' === get_theme_mod('restaurant_brunch_page_header_visibility', 'all-devices')) {
            $restaurant_brunch_return = false;
        }

        // Apply filters and return
        return apply_filters('restaurant_brunch_display_page_header', $restaurant_brunch_return);
    }
}

if ( ! function_exists( 'restaurant_brunch_page_header_style' ) ) {
    function restaurant_brunch_page_header_style() {
        $restaurant_brunch_style = get_theme_mod('restaurant_brunch_page_header_style', 'default');
        return apply_filters('restaurant_brunch_page_header_style', $restaurant_brunch_style);
    }
}

//Page Title Options
function restaurant_brunch_page_title_customizer_css() {
	$restaurant_brunch_layout_option = get_theme_mod( 'restaurant_brunch_page_header_layout', 'left' );
	$restaurant_brunch_custom_css = '';

	if ( $restaurant_brunch_layout_option === 'flex' ) {
		$restaurant_brunch_custom_css .= '
			.asterthemes-wrapper.page-header-inner {
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
		';
	} else {
		$restaurant_brunch_custom_css .= "
			.asterthemes-wrapper.page-header-inner {
				text-align: {$restaurant_brunch_layout_option};
			}
		";
	}

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_page_title_customizer_css' );

//Set Height
function restaurant_brunch_pagetitle_height_css() {
	$restaurant_brunch_height = get_theme_mod( 'restaurant_brunch_pagetitle_height', 50 );

	$restaurant_brunch_custom_css = "
		header.page-header {
			padding: {$restaurant_brunch_height}px 0;
		}
	";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_pagetitle_height_css' );

//Adjust Site Logo Width
function restaurant_brunch_site_logo_width() {
	$restaurant_brunch_logo_width = get_theme_mod( 'restaurant_brunch_site_logo_width', 150 );

	$restaurant_brunch_custom_css = "
		.site-logo img {
			max-width: {$restaurant_brunch_logo_width}px;
		}
	";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_site_logo_width' );

//Menu Font Size
function restaurant_brunch_menu_font_size_css() {
	$restaurant_brunch_font_size = get_theme_mod( 'restaurant_brunch_menu_font_size', 14 );

	$restaurant_brunch_custom_css = "
		.main-navigation a {
			font-size: {$restaurant_brunch_font_size}px;
		}
	";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_menu_font_size_css' );

//Menu Font Weight
function restaurant_brunch_menu_font_weight_css() {
	$restaurant_brunch_font_weight = get_theme_mod( 'restaurant_brunch_menu_font_weight', 400 );

	$restaurant_brunch_custom_css = "
		.main-navigation a {
		    font-weight: {$restaurant_brunch_font_weight};
		}
	";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_menu_font_weight_css' );

//Menu text transform
function restaurant_brunch_menu_text_transform_css() {
	$restaurant_brunch_text_transform = get_theme_mod( 'restaurant_brunch_menu_text_transform', 'uppercase' );

	$restaurant_brunch_custom_css = "
		.main-navigation a {
			text-transform: {$restaurant_brunch_text_transform};
		}
	";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_menu_text_transform_css' );

// Featured Image Dimension
function restaurant_brunch_custom_featured_image_css() {
    $restaurant_brunch_dimension = get_theme_mod('restaurant_brunch_blog_post_featured_image_dimension', 'default');
    $restaurant_brunch_width = get_theme_mod('restaurant_brunch_blog_post_featured_image_custom_width', '');
    $restaurant_brunch_height = get_theme_mod('restaurant_brunch_blog_post_featured_image_custom_height', '');
    
    if ($restaurant_brunch_dimension === 'custom' && $restaurant_brunch_width && $restaurant_brunch_height) {
        $restaurant_brunch_custom_css = "body:not(.single-post) .mag-post-single .mag-post-img img { width: {$restaurant_brunch_width}px !important; height: {$restaurant_brunch_height}px !important; }";
        wp_add_inline_style('restaurant-brunch-style', $restaurant_brunch_custom_css);
    }
}
add_action('wp_enqueue_scripts', 'restaurant_brunch_custom_featured_image_css');

// Featured Image Border Radius
function restaurant_brunch_featured_image_border_radius_css() {
	$restaurant_brunch_radius = get_theme_mod( 'restaurant_brunch_featured_image_border_radius', 10 );

	$restaurant_brunch_custom_css = "
		.mag-post-single img {
			border-radius: {$restaurant_brunch_radius}px;
		}
	";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_featured_image_border_radius_css' );

//Heading Font Size
function restaurant_brunch_sidebar_widget_font_size_css() {
	$restaurant_brunch_sidebar_widget_font_size = get_theme_mod( 'restaurant_brunch_sidebar_widget_font_size', 24 );

	$restaurant_brunch_custom_css = "
		h2.wp-block-heading,
		aside#secondary .widgettitle,
		aside#secondary .widget-title {
			font-size: {$restaurant_brunch_sidebar_widget_font_size}px;
		}
	";

	wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_sidebar_widget_font_size_css' );

// Woocommerce Related Products Settings
function restaurant_brunch_related_product_css() {
    $restaurant_brunch_related_product_show_hide = get_theme_mod( 'restaurant_brunch_related_product_show_hide', true );

    if ( ! $restaurant_brunch_related_product_show_hide ) {
        $restaurant_brunch_custom_css = '
            .related.products {
                display: none;
            }
        ';
        wp_add_inline_style( 'restaurant-brunch-style', $restaurant_brunch_custom_css );
    }
}
add_action( 'wp_enqueue_scripts', 'restaurant_brunch_related_product_css' );

// Product Sale Badge Position
function restaurant_brunch_product_sale_position_customizer_css() {
    $restaurant_brunch_position = get_theme_mod('restaurant_brunch_product_sale_position', 'left');
    $restaurant_brunch_css = '.woocommerce ul.products li.product .onsale {';
    if ($restaurant_brunch_position === 'left') {
        $restaurant_brunch_css .= 'right: auto; left: 0px;';
    } else {
        $restaurant_brunch_css .= 'left: auto; right: 0px;';
    }
    $restaurant_brunch_css .= '}';
    wp_add_inline_style('restaurant-brunch-style', $restaurant_brunch_css);
}
add_action('wp_enqueue_scripts', 'restaurant_brunch_product_sale_position_customizer_css');

// Footer Copyright Alignment
function restaurant_brunch_footer_copyright_alignment_css() {
    $restaurant_brunch_align = get_theme_mod('restaurant_brunch_footer_bottom_align', 'center');
    $restaurant_brunch_css = "
        .site-footer .site-footer-bottom .site-footer-bottom-wrapper {
            justify-content: {$restaurant_brunch_align};
        }
        @media screen and (max-width: 575px) {
            .site-footer .site-footer-bottom .site-footer-bottom-wrapper {
                justify-content: center;
                text-align: center;
            }
        }
    ";
    wp_add_inline_style('restaurant-brunch-style', $restaurant_brunch_css);
}
add_action('wp_enqueue_scripts', 'restaurant_brunch_footer_copyright_alignment_css');

// Footer Copyright Font Size
function restaurant_brunch_copyright_font_size_css() {
    $restaurant_brunch_font_size = get_theme_mod('restaurant_brunch_copyright_font_size', 16);
    $restaurant_brunch_css = ".site-footer-bottom .site-info span {
        font-size: {$restaurant_brunch_font_size}px;
    }";
    wp_add_inline_style('restaurant-brunch-style', $restaurant_brunch_css);
}
add_action('wp_enqueue_scripts', 'restaurant_brunch_copyright_font_size_css');

// Preloader Background Color
function restaurant_brunch_preloader_background_colors_css() {
    $restaurant_brunch_color = get_theme_mod('restaurant_brunch_preloader_background_color_setting', '');
    if (!empty($restaurant_brunch_color)) {
        $restaurant_brunch_css = "#loader {
            background-color: {$restaurant_brunch_color};
        }";
        wp_add_inline_style('restaurant-brunch-style', $restaurant_brunch_css);
    }
}
add_action('wp_enqueue_scripts', 'restaurant_brunch_preloader_background_colors_css');

// Preloader Background Image
function restaurant_brunch_preloader_background_image_css() {
    $restaurant_brunch_image_url = get_theme_mod('restaurant_brunch_preloader_background_image_setting', '');
    if (!empty($restaurant_brunch_image_url)) {
        $restaurant_brunch_css = "#loader {
            background-image: url('{$restaurant_brunch_image_url}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }";
        wp_add_inline_style('restaurant-brunch-style', $restaurant_brunch_css);
    }
}
add_action('wp_enqueue_scripts', 'restaurant_brunch_preloader_background_image_css');

// Footer Heading Alignment
function restaurant_brunch_footer_heading_alignment_css() {
    $restaurant_brunch_align = get_theme_mod('restaurant_brunch_footer_header_align', 'left');
    $restaurant_brunch_css = "
        .site-footer h4,
        footer#colophon h2.wp-block-heading,
        footer#colophon .widgettitle,
        footer#colophon .widget-title {
            text-align: {$restaurant_brunch_align};
        }
    ";
    wp_add_inline_style('restaurant-brunch-style', $restaurant_brunch_css);
}
add_action('wp_enqueue_scripts', 'restaurant_brunch_footer_heading_alignment_css');
