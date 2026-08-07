<?php
/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package restaurant_brunch
 */

$restaurant_brunch_menu_text_color = get_theme_mod('restaurant_brunch_menu_text_color', ''); 
$restaurant_brunch_sub_menu_text_color = get_theme_mod('restaurant_brunch_sub_menu_text_color', ''); 
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
    
	<?php wp_head(); ?>
</head>

<body <?php body_class(get_theme_mod('restaurant_brunch_website_layout', false) ? 'site-boxed--layout' : ''); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site asterthemes-site-wrapper">
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'restaurant-brunch' ); ?></a>
    <?php if (get_theme_mod('restaurant_brunch_enable_preloader', false)) : ?>
        <div id="loader" class="<?php echo esc_attr(get_theme_mod('restaurant_brunch_preloader_style', 'style1')); ?>">
            <div class="loader-container">
                <div id="preloader">
                    <?php 
                    $restaurant_brunch_preloader_style = get_theme_mod('restaurant_brunch_preloader_style', 'style1');
                    if ($restaurant_brunch_preloader_style === 'style1') : ?>
                        <!-- STYLE 1 -->
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/resource/loader.gif'); ?>" alt="<?php esc_attr_e('Loading...', 'restaurant-brunch'); ?>">
                    <?php elseif ($restaurant_brunch_preloader_style === 'style2') : ?>
                        <!-- STYLE 2 -->
                        <div class="dot"></div>
                    <?php elseif ($restaurant_brunch_preloader_style === 'style3') : ?>
                        <!-- STYLE 3 -->
                        <div class="bars">
                            <div class="bar"></div>
                            <div class="bar"></div>
                            <div class="bar"></div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
        <header id="masthead" class="site-header">
            <div class="header-main-wrapper">
                <?php if ( get_theme_mod( 'restaurant_brunch_enable_topbar', true ) === true ) {
                    $restaurant_brunch_discount_topbar_text = get_theme_mod( 'restaurant_brunch_discount_topbar_text', '' );
                    $restaurant_brunch_phone_number = get_theme_mod('restaurant_brunch_phone_number', '');
                    $restaurant_brunch_discount_topbar_button_text = get_theme_mod( 'restaurant_brunch_discount_topbar_button_text','Subscribe');
                    $restaurant_brunch_discount_topbar_button_url = get_theme_mod( 'restaurant_brunch_discount_topbar_button_url');
                ?>
                <?php if ( get_theme_mod( 'restaurant_brunch_enable_topbar', false ) ) {
                ?>
                <div class="top-header-part">
                    <div class="asterthemes-wrapper">
                        <div class="top-header-part-wrapper">
                            <?php if ( get_theme_mod( 'restaurant_brunch_enable_social', true ) === true ) { ?>
                                <div class="bottom-top-header-left-part">
                                    <div class="socail-search">
                                        <div class="social-icons">
                                            <?php
                                            if ( has_nav_menu( 'social' ) ) {
                                                wp_nav_menu(
                                                    array(
                                                        'menu_class'     => 'menu social-links',
                                                        'link_before'    => '<span class="screen-reader-text">',
                                                        'link_after'     => '</span>',
                                                        'theme_location' => 'social',
                                                    )
                                                );
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if ( ! empty( $restaurant_brunch_discount_topbar_text ) ) { ?>
                                <div class="header-contact-inner">
                                    <p><?php echo esc_html( $restaurant_brunch_discount_topbar_text ); ?><span><a href="<?php echo esc_url( $restaurant_brunch_discount_topbar_button_url ); ?>"><?php echo esc_html( $restaurant_brunch_discount_topbar_button_text ); ?></a></span></p>
                                </div>
                            <?php } ?>
                            <?php if ( ! empty( $restaurant_brunch_phone_number ) ) { ?>
                                <div class="bottom-top-header-right-part">
                                    <a href="tel:<?php echo esc_attr(  substr( $restaurant_brunch_phone_number, 0, 21 )  ); ?>"><i class="<?php echo esc_attr( get_theme_mod( 'restaurant_brunch_header_phone_icon', 'fas fa-phone-alt' ) ); ?>"></i><span><?php echo esc_html(  substr( $restaurant_brunch_phone_number, 0, 21 )  ); ?></span></a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
                <div class="bottom-header-outer-wrapper">
                    <div class="bottom-header-part">
                        <div class="asterthemes-wrapper">
                            <div class="bottom-header-part-wrapper hello <?php echo esc_attr( get_theme_mod( 'restaurant_brunch_enable_sticky_header', false ) ? 'sticky-header' : '' ); ?>">
                                <div class="bottom-header-left-part">
                                    <div class="site-branding">
                                        <?php
                                        // Check if the 'Enable Site Logo' setting is true.
                                        if ( get_theme_mod( 'restaurant_brunch_enable_site_logo', true ) ) {
                                            if ( has_custom_logo() ) { ?>
                                                <div class="site-logo">
                                                    <?php the_custom_logo(); ?>
                                                </div>
                                            <?php } 
                                        } ?>
                                        <div class="site-identity">
                                            <?php
                                            $restaurant_brunch_site_title_size = get_theme_mod('restaurant_brunch_site_title_size', 30);

                                            if (get_theme_mod('restaurant_brunch_enable_site_title_setting', true)) {
                                                if (is_front_page() && is_home()) : ?>
                                                    <h1 class="site-title" style="font-size: <?php echo esc_attr($restaurant_brunch_site_title_size); ?>px;">
                                                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                                                    </h1>
                                                <?php else : ?>
                                                    <p class="site-title" style="font-size: <?php echo esc_attr($restaurant_brunch_site_title_size); ?>px;">
                                                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>
                                                    </p>
                                                <?php endif;
                                            }

                                            if (get_theme_mod('restaurant_brunch_enable_tagline_setting', false)) :
                                                $restaurant_brunch_description = get_bloginfo('description', 'display');
                                                if ($restaurant_brunch_description || is_customize_preview()) : ?>
                                                    <p class="site-description"><?php echo esc_html($restaurant_brunch_description); ?></p>
                                                <?php endif;
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="navigation-menus">
                                    <div class="asterthemes-wrapper">
                                        <div class="navigation-part">
                                            <nav id="site-navigation" class="main-navigation">
                                                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                                                    <span></span>
                                                    <span></span>
                                                    <span></span>
                                                </button>
                                                <div class="main-navigation-links">
                                                    <?php
                                                        wp_nav_menu(
                                                            array(
                                                                'theme_location' => 'primary',
                                                            )
                                                        );
                                                    ?>
                                                </div>
                                                <style>
                                                    /* Main Menu Links */
                                                    .main-navigation ul li a, .menu a {
                                                        color: <?php echo esc_attr($restaurant_brunch_menu_text_color); ?>;
                                                    }

                                                    /* Submenu Links */
                                                    .main-navigation ul.children a, 
                                                    .home .main-navigation ul.children a, 
                                                    .main-navigation ul.menu li .sub-menu a, 
                                                    .home .main-navigation ul ul a {
                                                        color: <?php echo esc_attr($restaurant_brunch_sub_menu_text_color); ?>;
                                                    }
                                                </style>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                                <div class="bottom-header-right-part nav-box">
                                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                                        <a class="cart-customlocation" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo esc_attr__( 'View Shopping Cart', 'restaurant-brunch' ); ?>">
                                            <i class="fas fa-shopping-bag mr-2" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php $restaurant_brunch_enable_header_search_section = get_theme_mod( 'restaurant_brunch_enable_header_search_section', false );
                                            if ( $restaurant_brunch_enable_header_search_section ) : ?>
                                    <span class="search-main">
                                    <button class="search">
                                        <span class="btn">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                    <div class="form">                                      
                                        <?php if(class_exists('woocommerce')): ?>
                                            <form method="get" class="woocommerce-product-search woo-pro-search" action="<?php echo esc_url(home_url('/')); ?>">
                                                <label class="screen-reader-text" for="woocommerce-product-search-field"><?php esc_html_e('Search for:', 'restaurant-brunch'); ?></label>
                                                <input type="search" id="woocommerce-product-search-field" class="search-field " placeholder="<?php echo esc_attr('Search Here','restaurant-brunch'); ?>" value="<?php echo get_search_query(); ?>" name="s"/>
                                                <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                                                <input type="hidden" name="post_type" value="product"/>
                                            </form>
                                        <?php else : ?>

                                        <form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                                            <label>
                                                <span class="screen-reader-text"><?php echo esc_html( 'Search for:', 'label', 'restaurant-brunch' ); ?></span>
                                                <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search...', 'placeholder', 'restaurant-brunch' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                                            </label>
                                            <button type="submit" class="search-submit"><span class="btn"><i class="fa fa-search" aria-hidden="true"></i></span>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>     
            </div>
        </header>
<?php
if ( ! is_front_page() || is_home() ) {
	if ( is_front_page() ) {
		require get_template_directory() . '/sections/sections.php';
		restaurant_brunch_homepage_sections();

	}
	?>
    <?php
        if (!is_front_page() || is_home()) {
            get_template_part('page-header');
        }
    ?>
	<div id="content" class="site-content">
		<div class="asterthemes-wrapper">
			<div class="asterthemes-page">
			<?php } ?>