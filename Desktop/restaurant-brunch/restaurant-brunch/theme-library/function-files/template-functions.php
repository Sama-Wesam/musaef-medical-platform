<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package restaurant_brunch
 */

function restaurant_brunch_body_classes( $restaurant_brunch_classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$restaurant_brunch_classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$restaurant_brunch_classes[] = 'no-sidebar';
	}

	$restaurant_brunch_classes[] = restaurant_brunch_sidebar_layout();

	return $restaurant_brunch_classes;
}
add_filter( 'body_class', 'restaurant_brunch_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function restaurant_brunch_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'restaurant_brunch_pingback_header' );


/**
 * Get all posts for customizer Post content type.
 */
function restaurant_brunch_get_post_choices() {
	$restaurant_brunch_choices = array( '' => esc_html__( '--Select--', 'restaurant-brunch' ) );
	$restaurant_brunch_args    = array( 'numberposts' => -1 );
	$restaurant_brunch_posts   = get_posts( $restaurant_brunch_args );

	foreach ( $restaurant_brunch_posts as $restaurant_brunch_post ) {
		$restaurant_brunch_id             = $restaurant_brunch_post->ID;
		$restaurant_brunch_title          = $restaurant_brunch_post->post_title;
		$restaurant_brunch_choices[ $restaurant_brunch_id ] = $restaurant_brunch_title;
	}

	return $restaurant_brunch_choices;
}

/**
 * Get all pages for customizer Page content type.
 */
function restaurant_brunch_get_page_choices() {
	$restaurant_brunch_choices = array( '' => esc_html__( '--Select--', 'restaurant-brunch' ) );
	$restaurant_brunch_pages   = get_pages();

	foreach ( $restaurant_brunch_pages as $restaurant_brunch_page ) {
		$restaurant_brunch_choices[ $restaurant_brunch_page->ID ] = $restaurant_brunch_page->post_title;
	}

	return $restaurant_brunch_choices;
}

/**
 * Get all categories for customizer Category content type.
 */
function restaurant_brunch_get_post_cat_choices() {
	$restaurant_brunch_choices = array( '' => esc_html__( '--Select--', 'restaurant-brunch' ) );
	$restaurant_brunch_cats    = get_categories();

	foreach ( $restaurant_brunch_cats as $restaurant_brunch_cat ) {
		$restaurant_brunch_choices[ $restaurant_brunch_cat->term_id ] = $restaurant_brunch_cat->name;
	}

	return $restaurant_brunch_choices;
}

/**
 * Get all donation forms for customizer form content type.
 */
function restaurant_brunch_get_post_donation_form_choices() {
	$restaurant_brunch_choices = array( '' => esc_html__( '--Select--', 'restaurant-brunch' ) );
	$restaurant_brunch_posts   = get_posts(
		array(
			'post_type'   => 'give_forms',
			'numberposts' => -1,
		)
	);
	foreach ( $restaurant_brunch_posts as $restaurant_brunch_post ) {
		$restaurant_brunch_choices[ $restaurant_brunch_post->ID ] = $restaurant_brunch_post->post_title;
	}
	return $restaurant_brunch_choices;
}

if ( ! function_exists( 'restaurant_brunch_excerpt_length' ) ) :
	/**
	 * Excerpt length.
	 */
	function restaurant_brunch_excerpt_length( $restaurant_brunch_length ) {
		if ( is_admin() ) {
			return $restaurant_brunch_length;
		}

		return get_theme_mod( 'restaurant_brunch_excerpt_length', 20 );
	}
endif;
add_filter( 'excerpt_length', 'restaurant_brunch_excerpt_length', 999 );

if ( ! function_exists( 'restaurant_brunch_excerpt_more' ) ) :
	/**
	 * Excerpt more.
	 */
	function restaurant_brunch_excerpt_more( $restaurant_brunch_more ) {
		if ( is_admin() ) {
			return $restaurant_brunch_more;
		}

		return '&hellip;';
	}
endif;
add_filter( 'excerpt_more', 'restaurant_brunch_excerpt_more' );

if ( ! function_exists( 'restaurant_brunch_sidebar_layout' ) ) {
	/**
	 * Get sidebar layout.
	 */
	function restaurant_brunch_sidebar_layout() {
		$restaurant_brunch_sidebar_position      = get_theme_mod( 'restaurant_brunch_sidebar_position', 'right-sidebar' );
		$restaurant_brunch_sidebar_position_post = get_theme_mod( 'restaurant_brunch_post_sidebar_position', 'right-sidebar' );
		$restaurant_brunch_sidebar_position_page = get_theme_mod( 'restaurant_brunch_page_sidebar_position', 'right-sidebar' );

		if ( is_home() ) {
			$restaurant_brunch_sidebar_position = $restaurant_brunch_sidebar_position_post;
		} elseif ( is_page() ) {
			$restaurant_brunch_sidebar_position = $restaurant_brunch_sidebar_position_page;
		}

		return $restaurant_brunch_sidebar_position;
	}
}

if ( ! function_exists( 'restaurant_brunch_is_sidebar_enabled' ) ) {
	/**
	 * Check if sidebar is enabled.
	 */
	function restaurant_brunch_is_sidebar_enabled() {
		$restaurant_brunch_sidebar_position      = get_theme_mod( 'restaurant_brunch_sidebar_position', 'right-sidebar' );
		$restaurant_brunch_sidebar_position_post = get_theme_mod( 'restaurant_brunch_post_sidebar_position', 'right-sidebar' );
		$restaurant_brunch_sidebar_position_page = get_theme_mod( 'restaurant_brunch_page_sidebar_position', 'right-sidebar' );

		$restaurant_brunch_sidebar_enabled = true;
		if ( is_single() || is_archive() || is_search() ) {
			if ( 'no-sidebar' === $restaurant_brunch_sidebar_position ) {
				$restaurant_brunch_sidebar_enabled = false;
			}
		} elseif ( is_home() ) {
			if ( 'no-sidebar' === $restaurant_brunch_sidebar_position || 'no-sidebar' === $restaurant_brunch_sidebar_position_post ) {
				$restaurant_brunch_sidebar_enabled = false;
			}
		} elseif ( is_page() ) {
			if ( 'no-sidebar' === $restaurant_brunch_sidebar_position || 'no-sidebar' === $restaurant_brunch_sidebar_position_page ) {
				$restaurant_brunch_sidebar_enabled = false;
			}
		}
		return $restaurant_brunch_sidebar_enabled;
	}
}

if ( ! function_exists( 'restaurant_brunch_get_homepage_sections ' ) ) {
	/**
	 * Returns homepage sections.
	 */
	function restaurant_brunch_get_homepage_sections() {
		$restaurant_brunch_sections = array(
			'banner'  => esc_html__( 'Banner Section', 'restaurant-brunch' ),
			'services' => esc_html__( 'Services Section', 'restaurant-brunch' ),
		);
		return $restaurant_brunch_sections;
	}
}

/**
 * Renders customizer section link
 */
function restaurant_brunch_section_link( $restaurant_brunch_section_id ) {
	$restaurant_brunch_section_name      = str_replace( 'restaurant_brunch_', ' ', $restaurant_brunch_section_id );
	$restaurant_brunch_section_name      = str_replace( '_', ' ', $restaurant_brunch_section_name );
	$restaurant_brunch_starting_notation = '#';
	?>
	<span class="section-link">
		<span class="section-link-title"><?php echo esc_html( $restaurant_brunch_section_name ); ?></span>
	</span>
	<style type="text/css">
		<?php echo $restaurant_brunch_starting_notation . $restaurant_brunch_section_id; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>:hover .section-link {
			visibility: visible;
		}
	</style>
	<?php
}

/**
 * Adds customizer section link css
 */
function restaurant_brunch_section_link_css() {
	if ( is_customize_preview() ) {
		?>
		<style type="text/css">
			.section-link {
				visibility: hidden;
				background-color: black;
				position: relative;
				top: 80px;
				z-index: 99;
				left: 40px;
				color: #fff;
				text-align: center;
				font-size: 20px;
				border-radius: 10px;
				padding: 20px 10px;
				text-transform: capitalize;
			}

			.section-link-title {
				padding: 0 10px;
			}

			.banner-section {
				position: relative;
			}

			.banner-section .section-link {
				position: absolute;
				top: 100px;
			}
		</style>
		<?php
	}
}
add_action( 'wp_head', 'restaurant_brunch_section_link_css' );

/**
 * Breadcrumb.
 */
function restaurant_brunch_breadcrumb( $restaurant_brunch_args = array() ) {
	if ( ! get_theme_mod( 'restaurant_brunch_enable_breadcrumb', true ) ) {
		return;
	}

	$restaurant_brunch_args = array(
		'show_on_front' => false,
		'show_title'    => true,
		'show_browse'   => false,
	);
	breadcrumb_trail( $restaurant_brunch_args );
}
add_action( 'restaurant_brunch_breadcrumb', 'restaurant_brunch_breadcrumb', 10 );

/**
 * Add separator for breadcrumb trail.
 */
function restaurant_brunch_breadcrumb_trail_print_styles() {
	$restaurant_brunch_breadcrumb_separator = get_theme_mod( 'restaurant_brunch_breadcrumb_separator', '/' );

	$restaurant_brunch_style = '
		.trail-items li::after {
			content: "' . $restaurant_brunch_breadcrumb_separator . '";
		}'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	$restaurant_brunch_style = apply_filters( 'restaurant_brunch_breadcrumb_trail_inline_style', trim( str_replace( array( "\r", "\n", "\t", '  ' ), '', $restaurant_brunch_style ) ) );

	if ( $restaurant_brunch_style ) {
		echo "\n" . '<style type="text/css" id="breadcrumb-trail-css">' . $restaurant_brunch_style . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'restaurant_brunch_breadcrumb_trail_print_styles' );

/**
 * Pagination for archive.
 */
function restaurant_brunch_render_posts_pagination() {
	$restaurant_brunch_is_pagination_enabled = get_theme_mod( 'restaurant_brunch_enable_pagination', true );
	if ( $restaurant_brunch_is_pagination_enabled ) {
		$restaurant_brunch_pagination_type = get_theme_mod( 'restaurant_brunch_pagination_type', 'default' );
		if ( 'default' === $restaurant_brunch_pagination_type ) :
			the_posts_navigation();
		else :
			the_posts_pagination();
		endif;
	}
}
add_action( 'restaurant_brunch_posts_pagination', 'restaurant_brunch_render_posts_pagination', 10 );

/**
 * Pagination for single post.
 */
function restaurant_brunch_render_post_navigation() {
	the_post_navigation(
		array(
			'prev_text' => '<span>&#10229;</span> <span class="nav-title">%title</span>',
			'next_text' => '<span class="nav-title">%title</span> <span>&#10230;</span>',
		)
	);
}
add_action( 'restaurant_brunch_post_navigation', 'restaurant_brunch_render_post_navigation' );

/**
 * Adds footer copyright text.
 */
function restaurant_brunch_output_footer_copyright_content() {
    $restaurant_brunch_theme_data = wp_get_theme();
    $restaurant_brunch_copyright_text = get_theme_mod('restaurant_brunch_footer_copyright_text');

	if (!empty($restaurant_brunch_copyright_text)) {
        $restaurant_brunch_text = $restaurant_brunch_copyright_text;
    } else {

		$restaurant_brunch_default_text = '<a href="'. esc_url(__('https://asterthemes.com/products/restaurant-brunch','restaurant-brunch')) . '" target="_blank"> ' . esc_html($restaurant_brunch_theme_data->get('Name')) . '</a>' . '&nbsp;' . esc_html__('by', 'restaurant-brunch') . '&nbsp;<a target="_blank" href="' . esc_url($restaurant_brunch_theme_data->get('AuthorURI')) . '">' . esc_html(ucwords($restaurant_brunch_theme_data->get('Author'))) . '</a>';
		/* translators: %s: WordPress.org URL */
        $restaurant_brunch_default_text .= sprintf(esc_html__(' | Powered by %s', 'restaurant-brunch'), '<a href="' . esc_url(__('https://wordpress.org/', 'restaurant-brunch')) . '" target="_blank">WordPress</a>. ');

        $restaurant_brunch_text = $restaurant_brunch_default_text;

    }
    ?>
    <span><?php echo wp_kses_post($restaurant_brunch_text); ?></span>
    <?php
}
add_action('restaurant_brunch_footer_copyright', 'restaurant_brunch_output_footer_copyright_content');

/* Footer Social Icons */ 
function restaurant_brunch_footer_social_links() {

    if ( get_theme_mod('restaurant_brunch_enable_footer_icon_section', true) ) {

            ?>
            <div class="socialicons">
                <?php if ( get_theme_mod('restaurant_brunch_footer_facebook_link', 'https://www.facebook.com/') != '' ) { ?>
                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('restaurant_brunch_footer_facebook_link', 'https://www.facebook.com/')); ?>">
                        <i class="<?php echo esc_attr(get_theme_mod('restaurant_brunch_facebook_icon', 'fab fa-facebook-f')); ?>"></i>
                        <span class="screen-reader-text"><?php esc_html_e('Facebook', 'restaurant-brunch'); ?></span>
                    </a>
                <?php } ?>
                <?php if ( get_theme_mod('restaurant_brunch_footer_twitter_link', 'https://x.com/') != '' ) { ?>
                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('restaurant_brunch_footer_twitter_link', 'https://x.com/')); ?>">
                        <i class="<?php echo esc_attr(get_theme_mod('restaurant_brunch_twitter_icon', 'fab fa-twitter')); ?>"></i>
                        <span class="screen-reader-text"><?php esc_html_e('Twitter', 'restaurant-brunch'); ?></span>
                    </a>
                <?php } ?>
				<?php if ( get_theme_mod('restaurant_brunch_footer_instagram_link', 'https://www.instagram.com/') != '' ) { ?>
                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('restaurant_brunch_footer_instagram_link', 'https://www.instagram.com/')); ?>">
                        <i class="<?php echo esc_attr(get_theme_mod('restaurant_brunch_instagram_icon', 'fab fa-instagram')); ?>"></i>
                        <span class="screen-reader-text"><?php esc_html_e('Instagram', 'restaurant-brunch'); ?></span>
                    </a>
                <?php } ?>
                <?php if ( get_theme_mod('restaurant_brunch_footer_linkedin_link', 'https://in.linkedin.com/') != '' ) { ?>
                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('restaurant_brunch_footer_linkedin_link', 'https://in.linkedin.com/')); ?>">
                        <i class="<?php echo esc_attr(get_theme_mod('restaurant_brunch_linkedin_icon', 'fab fa-linkedin')); ?>"></i>
                        <span class="screen-reader-text"><?php esc_html_e('Linkedin', 'restaurant-brunch'); ?></span>
                    </a>
                <?php } ?>
				<?php if ( get_theme_mod('restaurant_brunch_footer_youtube_link', 'https://www.youtube.com/') != '' ) { ?>
                    <a target="_blank" href="<?php echo esc_url(get_theme_mod('restaurant_brunch_footer_youtube_link', 'https://www.youtube.com/')); ?>">
                        <i class="<?php echo esc_attr(get_theme_mod('restaurant_brunch_youtube_icon', 'fab fa-youtube')); ?>"></i>
                        <span class="screen-reader-text"><?php esc_html_e('Youtube', 'restaurant-brunch'); ?></span>
                    </a>
                <?php } ?>
            </div>
            <?php
    }
}
add_action('wp_footer', 'restaurant_brunch_footer_social_links');

if ( ! function_exists( 'restaurant_brunch_footer_widget' ) ) :
	function restaurant_brunch_footer_widget() {
		$restaurant_brunch_footer_widget_column = get_theme_mod('restaurant_brunch_footer_widget_column','4');

		$restaurant_brunch_column_class = '';
		if ($restaurant_brunch_footer_widget_column == '1') {
			$restaurant_brunch_column_class = 'one-column';
		} elseif ($restaurant_brunch_footer_widget_column == '2') {
			$restaurant_brunch_column_class = 'two-columns';
		} elseif ($restaurant_brunch_footer_widget_column == '3') {
			$restaurant_brunch_column_class = 'three-columns';
		} else {
			$restaurant_brunch_column_class = 'four-columns';
		}
	
		if($restaurant_brunch_footer_widget_column !== ''): 
		?>
		<div class="dt_footer-widgets <?php echo esc_attr($restaurant_brunch_column_class); ?>">
			<div class="footer-widgets-column">
				<?php
				$footer_widgets_active = false;

				// Loop to check if any footer widget is active
				for ($restaurant_brunch_i = 1; $restaurant_brunch_i <= $restaurant_brunch_footer_widget_column; $restaurant_brunch_i++) {
					if (is_active_sidebar('restaurant-brunch-footer-widget-' . $restaurant_brunch_i)) {
						$footer_widgets_active = true;
						break;
					}
				}

				if ($footer_widgets_active) {
					// Display active footer widgets
					for ($restaurant_brunch_i = 1; $restaurant_brunch_i <= $restaurant_brunch_footer_widget_column; $restaurant_brunch_i++) {
						if (is_active_sidebar('restaurant-brunch-footer-widget-' . $restaurant_brunch_i)) : ?>
							<div class="footer-one-column">
								<?php dynamic_sidebar('restaurant-brunch-footer-widget-' . $restaurant_brunch_i); ?>
							</div>
						<?php endif;
					}
				} else {
				?>
				<div class="footer-one-column default-widgets">
					<aside id="search-2" class="widget widget_search default_footer_search">
						<div class="widget-header">
							<h4 class="widget-title"><?php esc_html_e('Search Here', 'restaurant-brunch'); ?></h4>
						</div>
						<?php get_search_form(); ?>
					</aside>
				</div>
				<div class="footer-one-column default-widgets">
					<aside id="recent-posts-2" class="widget widget_recent_entries">
						<h2 class="widget-title"><?php esc_html_e('Recent Posts', 'restaurant-brunch'); ?></h2>
						<ul>
							<?php
							$recent_posts = wp_get_recent_posts(array(
								'numberposts' => 5,
								'post_status' => 'publish',
							));
							foreach ($recent_posts as $post) {
								echo '<li><a href="' . esc_url(get_permalink($post['ID'])) . '">' . esc_html($post['post_title']) . '</a></li>';
							}
							wp_reset_query();
							?>
						</ul>
					</aside>
				</div>
				<div class="footer-one-column default-widgets">
					<aside id="recent-comments-2" class="widget widget_recent_comments">
						<h2 class="widget-title"><?php esc_html_e('Recent Comments', 'restaurant-brunch'); ?></h2>
						<ul>
							<?php
							$recent_comments = get_comments(array(
								'number' => 5,
								'status' => 'approve',
							));
							foreach ($recent_comments as $comment) {
								echo '<li><a href="' . esc_url(get_comment_link($comment)) . '">' .
									/* translators: %s: details. */
									sprintf(esc_html__('Comment on %s', 'restaurant-brunch'), get_the_title($comment->comment_post_ID)) .
									'</a></li>';
							}
							?>
						</ul>
					</aside>
				</div>
				<div class="footer-one-column default-widgets">
					<aside id="categories" class="widget py-3" role="complementary" aria-label="<?php esc_attr_e('footer1', 'restaurant-brunch'); ?>">
                        <h3 class="widget-title"><?php esc_html_e('Categories', 'restaurant-brunch'); ?></h3>
                        <ul>
                            <?php wp_list_categories('title_li='); ?>
                        </ul>
                    </aside>
				</div>
			</div>
			<?php } ?>
		</div>
		<?php
		endif;
	}
	endif;
add_action( 'restaurant_brunch_footer_widget', 'restaurant_brunch_footer_widget' );


function restaurant_brunch_footer_text_transform_css() {
    $restaurant_brunch_footer_text_transform = get_theme_mod('footer_text_transform', 'none');
    ?>
    <style type="text/css">
        .site-footer h4,footer#colophon h2.wp-block-heading,footer#colophon .widgettitle,footer#colophon .widget-title {
            text-transform: <?php echo esc_html($restaurant_brunch_footer_text_transform); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'restaurant_brunch_footer_text_transform_css');

/**
 * GET START FUNCTION
 */

 function restaurant_brunch_getpage_css($hook) {
	wp_enqueue_script( 'restaurant-brunch-admin-script', get_template_directory_uri() . '/resource/js/restaurant-brunch-admin-notice-script.js', array( 'jquery' ) );
    wp_localize_script( 'restaurant-brunch-admin-script', 'restaurant_brunch_ajax_object',
        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
    );
    wp_enqueue_style( 'restaurant-brunch-notice-style', get_template_directory_uri() . '/resource/css/notice.css' );
}

add_action( 'admin_enqueue_scripts', 'restaurant_brunch_getpage_css' );

add_action('wp_ajax_restaurant_brunch_dismissable_notice', 'restaurant_brunch_dismissable_notice');
function restaurant_brunch_switch_theme() {
    delete_user_meta(get_current_user_id(), 'restaurant_brunch_dismissable_notice');
}
add_action('after_switch_theme', 'restaurant_brunch_switch_theme');
function restaurant_brunch_dismissable_notice() {
    update_user_meta(get_current_user_id(), 'restaurant_brunch_dismissable_notice', true);
    die();
}

function restaurant_brunch_deprecated_hook_admin_notice() {
    global $pagenow;
    
    // Check if the current page is the one where you don't want the notice to appear
    if ( $pagenow === 'themes.php' && isset( $_GET['page'] ) && $_GET['page'] === 'restaurant-brunch-getting-started' ) {
        return;
    }

    $dismissed = get_user_meta( get_current_user_id(), 'restaurant_brunch_dismissable_notice', true );
    if ( !$dismissed) { ?>
        <div class="getstrat updated notice notice-success is-dismissible notice-get-started-class">
            <div class="at-admin-content" >
                <h2><?php esc_html_e('Welcome to Restaurant Brunch', 'restaurant-brunch'); ?></h2>
                <p><?php _e('Explore the features of our Pro Theme and take your Restaurant Brunch journey to the next level.', 'restaurant-brunch'); ?></p>
                <p ><?php _e('Get Started With Theme By Clicking On Getting Started.', 'restaurant-brunch'); ?><p>
                <div style="display: flex; justify-content: center;">
                    <a class="admin-notice-btn button button-primary button-hero" href="<?php echo esc_url( admin_url( 'themes.php?page=restaurant-brunch-getting-started' )); ?>"><?php esc_html_e( 'Get started', 'restaurant-brunch' ) ?></a>
                    <a  class="admin-notice-btn button button-primary button-hero" target="_blank" href="https://demo.asterthemes.com/restaurant-brunch/"><?php esc_html_e('View Demo', 'restaurant-brunch') ?></a>
                    <a  class="admin-notice-btn button button-primary button-hero" target="_blank" href="https://asterthemes.com/products/veterinary-wordpress-theme"><?php esc_html_e('Buy Now', 'restaurant-brunch') ?></a>
                    <a  class="admin-notice-btn button button-primary button-hero" target="_blank" href="https://demo.asterthemes.com/docs/restaurant-brunch-free/"><?php esc_html_e('Free Doc', 'restaurant-brunch') ?></a>
                </div>
            </div>
            <div class="at-admin-image">
                <img style="width: 100%;max-width: 320px;line-height: 40px;display: inline-block;vertical-align: top;border: 2px solid #ddd;border-radius: 4px;" src="<?php echo esc_url(get_stylesheet_directory_uri()) .'/screenshot.png'; ?>" />
            </div>
        </div>
    <?php }
}

add_action( 'admin_notices', 'restaurant_brunch_deprecated_hook_admin_notice' );


//Admin Notice For Getstart
function restaurant_brunch_ajax_notice_handler() {
    if ( isset( $_POST['type'] ) ) {
        $type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
        update_option( 'dismissed-' . $type, TRUE );
    }
}