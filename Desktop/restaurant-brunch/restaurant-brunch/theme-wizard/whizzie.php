<?php
/**
 * Wizard
 *
 * @package Whizzie
 * @author Aster Themes
 * @since 1.0.0
 */

class Whizzie {

	protected $version = '1.1.0';
	protected $theme_name = '';
	protected $theme_title = '';
	protected $page_slug = '';
	protected $page_title = '';
	protected $config_steps = array();
	public $plugin_path;
	public $parent_slug;
	/**
	 * Relative plugin url for this plugin folder
	 * @since 1.0.0
	 * @var string
	 */
	protected $plugin_url = '';

	/**
	 * TGMPA instance storage
	 *
	 * @var object
	 */
	protected $tgmpa_instance;

	/**
	 * TGMPA Menu slug
	 *
	 * @var string
	 */
	protected $tgmpa_menu_slug = 'tgmpa-install-plugins';

	/**
	 * TGMPA Menu url
	 *
	 * @var string
	 */
	protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';

	/*** Constructor ***
	* @param $config	Our config parameters
	*/
	public function __construct( $config ) {
		$this->set_vars( $config );
		$this->init();
	}

	/**
	* Set some settings
	* @since 1.0.0
	* @param $config	Our config parameters
	*/

	public function set_vars( $config ) {
		// require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/class-tgm-plugin-activation.php';
		require_once trailingslashit( WHIZZIE_DIR ) . 'tgm/tgm.php';

		if( isset( $config['page_slug'] ) ) {
			$this->page_slug = esc_attr( $config['page_slug'] );
		}
		if( isset( $config['page_title'] ) ) {
			$this->page_title = esc_attr( $config['page_title'] );
		}
		if( isset( $config['steps'] ) ) {
			$this->config_steps = $config['steps'];
		}

		$this->plugin_path = trailingslashit( dirname( __FILE__ ) );
		$relative_url = str_replace( get_template_directory(), '', $this->plugin_path );
		$this->plugin_url = trailingslashit( get_template_directory_uri() . $relative_url );
		$current_theme = wp_get_theme();
		$this->theme_title = $current_theme->get( 'Name' );
		$this->theme_name = strtolower( preg_replace( '#[^a-zA-Z]#', '', $current_theme->get( 'Name' ) ) );
		$this->page_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_page_slug', $this->theme_name . '-wizard' );
		$this->parent_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_parent_slug', '' );
	}

	/**
	 * Hooks and filters
	 * @since 1.0.0
	 */
	public function init() {
		if ( class_exists( 'TGM_Plugin_Activation' ) && isset( $GLOBALS['tgmpa'] ) ) {
			add_action( 'init', array( $this, 'get_tgmpa_instance' ), 30 );
			add_action( 'init', array( $this, 'set_tgmpa_url' ), 40 );
		}
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'menu_page' ) );
		add_action( 'admin_init', array( $this, 'get_plugins' ), 30 );
		add_filter( 'tgmpa_load', array( $this, 'tgmpa_load' ), 10, 1 );
		add_action( 'wp_ajax_setup_plugins', array( $this, 'setup_plugins' ) );
		add_action( 'wp_ajax_setup_widgets', array( $this, 'setup_widgets' ) );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'theme-wizard-style', get_template_directory_uri() . '/theme-wizard/assets/css/theme-wizard-style.css');
		wp_register_script( 'theme-wizard-script', get_template_directory_uri() . '/theme-wizard/assets/js/theme-wizard-script.js', array( 'jquery' ), );

		wp_localize_script(
			'theme-wizard-script',
			'restaurant_brunch_whizzie_params',
			array(
				'ajaxurl' 		=> admin_url( 'admin-ajax.php' ),
				'verify_text'	=> esc_html( 'verifying', 'restaurant-brunch' )
			)
		);
		wp_enqueue_script( 'theme-wizard-script' );
	}

	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function tgmpa_load( $status ) {
		return is_admin() || current_user_can( 'install_themes' );
	}

	/**
	 * Get configured TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2*/
	public function get_tgmpa_instance() {
		$this->tgmpa_instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
	}

	/**
	 * Update $tgmpa_menu_slug and $tgmpa_parent_slug from TGMPA instance
	 *
	 * @access public
	 * @since 1.1.2
	 */
	public function set_tgmpa_url() {
		$this->tgmpa_menu_slug = ( property_exists( $this->tgmpa_instance, 'menu' ) ) ? $this->tgmpa_instance->menu : $this->tgmpa_menu_slug;
		$this->tgmpa_menu_slug = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_menu_slug', $this->tgmpa_menu_slug );
		$tgmpa_parent_slug = ( property_exists( $this->tgmpa_instance, 'parent_slug' ) && $this->tgmpa_instance->parent_slug !== 'themes.php' ) ? 'admin.php' : 'themes.php';
		$this->tgmpa_url = apply_filters( $this->theme_name . '_theme_setup_wizard_tgmpa_url', $tgmpa_parent_slug . '?page=' . $this->tgmpa_menu_slug );
	}

	/***        Make a modal screen for the wizard        ***/
	
	public function menu_page() {
		add_theme_page( esc_html( $this->page_title ), esc_html( $this->page_title ), 'manage_options', $this->page_slug, array( $this, 'restaurant_brunch_setup_wizard' ) );
	}

	/***        Make an interface for the wizard        ***/

	public function wizard_page() {
		tgmpa_load_bulk_installer();
		// install plugins with TGM.
		if ( ! class_exists( 'TGM_Plugin_Activation' ) || ! isset( $GLOBALS['tgmpa'] ) ) {
			die( 'Failed to find TGM' );
		}
		$url = wp_nonce_url( add_query_arg( array( 'plugins' => 'go' ) ), 'whizzie-setup' );

		// copied from TGM
		$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.
		$fields = array_keys( $_POST ); // Extra fields to pass to WP_Filesystem.
		if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, $fields ) ) ) {
			return true; // Stop the normal page form from displaying, credential request form will be shown.
		}
		// Now we have some credentials, setup WP_Filesystem.
		if ( ! WP_Filesystem( $creds ) ) {
			// Our credentials were no good, ask the user for them again.
			request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, $fields );
			return true;
		}
		/* If we arrive here, we have the filesystem */ ?>
		<div class="main-wrap">
			<?php
			echo '<div class="card whizzie-wrap">';
				// The wizard is a list with only one item visible at a time
				$steps = $this->get_steps();
				echo '<ul class="whizzie-menu">';
				foreach( $steps as $step ) {
					$class = 'step step-' . esc_attr( $step['id'] );
					echo '<li data-step="' . esc_attr( $step['id'] ) . '" class="' . esc_attr( $class ) . '">';
						printf( '<h2>%s</h2>', esc_html( $step['title'] ) );
						// $content is split into summary and detail
						$content = call_user_func( array( $this, $step['view'] ) );
						if( isset( $content['summary'] ) ) {
							printf(
								'<div class="summary">%s</div>',
								wp_kses_post( $content['summary'] )
							);
						}
						if( isset( $content['detail'] ) ) {
							// Add a link to see more detail
							printf( '<p><a href="#" class="more-info">%s</a></p>', __( 'More Info', 'restaurant-brunch' ) );
							printf(
								'<div class="detail">%s</div>',
								$content['detail'] // Need to escape this
							);
						}
						// The next button
						if( isset( $step['button_text'] ) && $step['button_text'] ) {
							printf(
								'<div class="button-wrap"><a href="#" class="button button-primary do-it" data-callback="%s" data-step="%s">%s</a></div>',
								esc_attr( $step['callback'] ),
								esc_attr( $step['id'] ),
								esc_html( $step['button_text'] )
							);
						}
					echo '</li>';
				}
				echo '</ul>';
				?>
				<div class="step-loading"><span class="spinner"></span></div>
			</div><!-- .whizzie-wrap -->

		</div><!-- .wrap -->
	<?php }



	public function activation_page() {
		?>
		<div class="main-wrap">
			<h3><?php esc_html_e('Theme Setup Wizard','restaurant-brunch'); ?></h3>
		</div>
		<?php
	}

	public function restaurant_brunch_setup_wizard(){

			$display_string = '';

			$body = [
				'home_url'					 => home_url(),
				'theme_text_domain'	 => wp_get_theme()->get( 'TextDomain' )
			];

			$body = wp_json_encode( $body );
			$options = [
				'body'        => $body,
				'sslverify' 	=> false,
				'headers'     => [
					'Content-Type' => 'application/json',
				]
			];

			//custom function about theme customizer
			$return = add_query_arg( array()) ;
			$theme = wp_get_theme( 'restaurant-brunch' );

			?>
				<div class="wrapper-info get-stared-page-wrap">
					<div class="tab-sec theme-option-tab">
						<div id="demo_offer" class="tabcontent">
							<?php $this->wizard_page(); ?>
						</div>
					</div>
				</div>
			<?php
	}
	

	/**
	* Set options for the steps
	* Incorporate any options set by the theme dev
	* Return the array for the steps
	* @return Array
	*/
	public function get_steps() {
		$dev_steps = $this->config_steps;
		$steps = array(
			'intro' => array(
				'id'			=> 'intro',
				'title'			=> __( 'Welcome to ', 'restaurant-brunch' ) . $this->theme_title,
				'icon'			=> 'dashboard',
				'view'			=> 'get_step_intro', // Callback for content
				'callback'		=> 'do_next_step', // Callback for JS
				'button_text'	=> __( 'Start Now', 'restaurant-brunch' ),
				'can_skip'		=> false // Show a skip button?
			),
			'plugins' => array(
				'id'			=> 'plugins',
				'title'			=> __( 'Plugins', 'restaurant-brunch' ),
				'icon'			=> 'admin-plugins',
				'view'			=> 'get_step_plugins',
				'callback'		=> 'install_plugins',
				'button_text'	=> __( 'Install Plugins', 'restaurant-brunch' ),
				'can_skip'		=> true
			),
			'widgets' => array(
				'id'			=> 'widgets',
				'title'			=> __( 'Demo Importer', 'restaurant-brunch' ),
				'icon'			=> 'welcome-widgets-menus',
				'view'			=> 'get_step_widgets',
				'callback'		=> 'install_widgets',
				'button_text'	=> __( 'Import Demo', 'restaurant-brunch' ),
				'can_skip'		=> true
			),
			'done' => array(
				'id'			=> 'done',
				'title'			=> __( 'All Done', 'restaurant-brunch' ),
				'icon'			=> 'yes',
				'view'			=> 'get_step_done',
				'callback'		=> ''
			)
		);

		// Iterate through each step and replace with dev config values
		if( $dev_steps ) {
			// Configurable elements - these are the only ones the dev can update from config.php
			$can_config = array( 'title', 'icon', 'button_text', 'can_skip' );
			foreach( $dev_steps as $dev_step ) {
				// We can only proceed if an ID exists and matches one of our IDs
				if( isset( $dev_step['id'] ) ) {
					$id = $dev_step['id'];
					if( isset( $steps[$id] ) ) {
						foreach( $can_config as $element ) {
							if( isset( $dev_step[$element] ) ) {
								$steps[$id][$element] = $dev_step[$element];
							}
						}
					}
				}
			}
		}
		return $steps;
	}

	/*** Display the content for the intro step ***/
	public function get_step_intro() { ?>
		<div class="summary">
			<p style="text-align: center;"><?php esc_html_e( 'Thank you for choosing our theme! We are excited to help you get started with your new website.', 'restaurant-brunch' ); ?></p>
			<p style="text-align: center;"><?php esc_html_e( 'To ensure you make the most of our theme, we recommend following the setup steps outlined here. This process will help you configure the theme to best suit your needs and preferences. Click on the "Start Now" button to begin the setup.', 'restaurant-brunch' ); ?></p>
		</div>
	<?php }
	
	/**
	 * Get the content for the plugins step
	 * @return $content Array
	 */
	public function get_step_plugins() {
		$plugins = $this->get_plugins();
		$content = array(); ?>
			<div class="summary">
				<p>
					<?php esc_html_e('Additional plugins always make your website exceptional. Install these plugins by clicking the install button. You may also deactivate them from the dashboard.','restaurant-brunch') ?>
				</p>
			</div>
		<?php // The detail element is initially hidden from the user
		$content['detail'] = '<ul class="whizzie-do-plugins">';
		// Add each plugin into a list
		foreach( $plugins['all'] as $slug=>$plugin ) {
			$content['detail'] .= '<li data-slug="' . esc_attr( $slug ) . '">' . esc_html( $plugin['name'] ) . '<span>';
			$keys = array();
			if ( isset( $plugins['install'][ $slug ] ) ) {
			    $keys[] = 'Installation';
			}
			if ( isset( $plugins['update'][ $slug ] ) ) {
			    $keys[] = 'Update';
			}
			if ( isset( $plugins['activate'][ $slug ] ) ) {
			    $keys[] = 'Activation';
			}
			$content['detail'] .= implode( ' and ', $keys ) . ' required';
			$content['detail'] .= '</span></li>';
		}
		$content['detail'] .= '</ul>';

		return $content;
	}

	/*** Display the content for the widgets step ***/
	public function get_step_widgets() { ?>
		<div class="summary">
			<p><?php esc_html_e('To get started, use the button below to import demo content and add widgets to your site. After installation, you can manage settings and customize your site using the Customizer. Enjoy your new theme!', 'restaurant-brunch'); ?></p>
		</div>
	<?php }

	/***        Print the content for the final step        ***/

	public function get_step_done() { ?>
		<div id="aster-demo-setup-guid">
			<div class="aster-setup-menu">
				<h3><?php esc_html_e('Setup Navigation Menu','restaurant-brunch'); ?></h3>
				<p><?php esc_html_e('Follow the following Steps to Setup Menu','restaurant-brunch'); ?></p>
				<h4><?php esc_html_e('A) Create Pages','restaurant-brunch'); ?></h4>
				<ol>
					<li><?php esc_html_e('Go to Dashboard >> Pages >> Add New','restaurant-brunch'); ?></li>
					<li><?php esc_html_e('Enter Page Details And Save Changes','restaurant-brunch'); ?></li>
				</ol>
				<h4><?php esc_html_e('B) Add Pages To Menu','restaurant-brunch'); ?></h4>
				<ol>
					<li><?php esc_html_e('Go to Dashboard >> Appearance >> Menu','restaurant-brunch'); ?></li>
					<li><?php esc_html_e('Click On The Create Menu Option','restaurant-brunch'); ?></li>
					<li><?php esc_html_e('Select The Pages And Click On The Add to Menu Button','restaurant-brunch'); ?></li>
					<li><?php esc_html_e('Select Primary Menu From The Menu Setting','restaurant-brunch'); ?></li>
					<li><?php esc_html_e('Click On The Save Menu Button','restaurant-brunch'); ?></li>
				</ol>
			</div>
			<div class="aster-setup-widget">
				<h3><?php esc_html_e('Setup Footer Widgets','restaurant-brunch'); ?></h3>
				<p><?php esc_html_e('Follow the following Steps to Setup Footer Widgets','restaurant-brunch'); ?></p>
				<ol>
					<li><?php esc_html_e('Go to Dashboard >> Appearance >> Widgets','restaurant-brunch'); ?></li>
					<li><?php esc_html_e('Drag And Add The Widgets In The Footer Columns','restaurant-brunch'); ?></li>
				</ol>
			</div>
			<div style="display:flex; justify-content: center; margin-top: 20px; gap:20px">
				<div class="aster-setup-finish">
					<a target="_blank" href="<?php echo esc_url(home_url()); ?>" class="button button-primary">Visit Site</a>
				</div>
				<div class="aster-setup-finish">
					<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>" class="button button-primary">Customize Your Demo</a>
				</div>
				<div class="aster-setup-finish">
					<a target="_blank" href="<?php echo esc_url( admin_url('themes.php?page=restaurant-brunch-getting-started') ); ?>" class="button button-primary">Getting Started</a>
				</div>
			</div>
		</div>
	<?php }

	/***       Get the plugins registered with TGMPA       ***/
	public function get_plugins() {
		$instance = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
		$plugins = array(
			'all' 		=> array(),
			'install'	=> array(),
			'update'	=> array(),
			'activate'	=> array()
		);
		foreach( $instance->plugins as $slug=>$plugin ) {
			if( $instance->is_plugin_active( $slug ) && false === $instance->does_plugin_have_update( $slug ) ) {
				// Plugin is installed and up to date
				continue;
			} else {
				$plugins['all'][$slug] = $plugin;
				if( ! $instance->is_plugin_installed( $slug ) ) {
					$plugins['install'][$slug] = $plugin;
				} else {
					if( false !== $instance->does_plugin_have_update( $slug ) ) {
						$plugins['update'][$slug] = $plugin;
					}
					if( $instance->can_plugin_activate( $slug ) ) {
						$plugins['activate'][$slug] = $plugin;
					}
				}
			}
		}
		return $plugins;
	}


	public function setup_plugins() {
		$json = array();
		// send back some json we use to hit up TGM
		$plugins = $this->get_plugins();

		// what are we doing with this plugin?
		foreach ( $plugins['activate'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-activate',
					'action2'       => - 1,
					'message'       => esc_html__( 'Activating Plugin','restaurant-brunch' ),
				);
				break;
			}
		}
		foreach ( $plugins['update'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-update',
					'action2'       => - 1,
					'message'       => esc_html__( 'Updating Plugin','restaurant-brunch' ),
				);
				break;
			}
		}
		foreach ( $plugins['install'] as $slug => $plugin ) {
			if ( $_POST['slug'] == $slug ) {
				$json = array(
					'url'           => admin_url( $this->tgmpa_url ),
					'plugin'        => array( $slug ),
					'tgmpa-page'    => $this->tgmpa_menu_slug,
					'plugin_status' => 'all',
					'_wpnonce'      => wp_create_nonce( 'bulk-plugins' ),
					'action'        => 'tgmpa-bulk-install',
					'action2'       => - 1,
					'message'       => esc_html__( 'Installing Plugin','restaurant-brunch' ),
				);
				break;
			}
		}
		if ( $json ) {
			$json['hash'] = md5( serialize( $json ) ); // used for checking if duplicates happen, move to next plugin
			wp_send_json( $json );
		} else {
			wp_send_json( array( 'done' => 1, 'message' => esc_html__( 'Success','restaurant-brunch' ) ) );
		}
		exit;
	}

	/***------------------------------------------------- Imports the Demo Content* @since 1.1.0 ----------------------------------------------****/


	//                      ------------- MENUS -----------------                    //

	public function restaurant_brunch_customizer_primary_menu(){

		// ------- Create Primary Menu --------
		$restaurant_brunch_menuname = $restaurant_brunch_themename . 'Main Menu';
		$restaurant_brunch_bpmenulocation = 'primary';
		$restaurant_brunch_menu_exists = wp_get_nav_menu_object( $restaurant_brunch_menuname );

		if( !$restaurant_brunch_menu_exists){
			$restaurant_brunch_menu_id = wp_create_nav_menu($restaurant_brunch_menuname);
			$restaurant_brunch_parent_item = 
			wp_update_nav_menu_item($restaurant_brunch_menu_id, 0, array(
				'menu-item-title' =>  __('Home','restaurant-brunch'),
				'menu-item-classes' => 'home',
				'menu-item-url' => home_url( '/' ),
				'menu-item-status' => 'publish'));

			wp_update_nav_menu_item($restaurant_brunch_menu_id, 0, array(
				'menu-item-title'   => __('Pages', 'restaurant-brunch'),
				'menu-item-classes' => 'pages',
				'menu-item-url'     => get_permalink(get_page_by_title('Pages')),
				'menu-item-status'  => 'publish'
			));

			wp_update_nav_menu_item($restaurant_brunch_menu_id, 0, array(
				'menu-item-title' =>  __('Shop','restaurant-brunch'),
				'menu-item-classes' => 'shop',
				'menu-item-url' => get_permalink(get_page_by_title('Shop')),
				'menu-item-status' => 'publish'));

			wp_update_nav_menu_item($restaurant_brunch_menu_id, 0, array(
				'menu-item-title' =>  __('About','restaurant-brunch'),
				'menu-item-classes' => 'about',
				'menu-item-url' => get_permalink(get_page_by_title('About')),
				'menu-item-status' => 'publish'));	

			wp_update_nav_menu_item($restaurant_brunch_menu_id, 0, array(
				'menu-item-title'   => __('Blogs', 'restaurant-brunch'),
				'menu-item-classes' => 'blog',
				'menu-item-url'     => get_permalink(get_page_by_title('Blogs')),
				'menu-item-status'  => 'publish'
			));
			
			if( !has_nav_menu( $restaurant_brunch_bpmenulocation ) ){
				$locations = get_theme_mod('nav_menu_locations');
				$locations[$restaurant_brunch_bpmenulocation] = $restaurant_brunch_menu_id;
				set_theme_mod( 'nav_menu_locations', $locations );
			}
		}
	}

	public function restaurant_brunch_customizer_socail_nav_menu() {

		// ------- Create Social Menu --------
		$restaurant_brunch_menuname = $restaurant_brunch_themename . 'Social Menu';
		$restaurant_brunch_bpmenulocation = 'social';
		$restaurant_brunch_menu_exists = wp_get_nav_menu_object( $restaurant_brunch_menuname );

		if( !$restaurant_brunch_menu_exists){
			$restaurant_brunch_menu_id = wp_create_nav_menu($restaurant_brunch_menuname);

			wp_update_nav_menu_item( $restaurant_brunch_menu_id, 0, array(
				'menu-item-title'  => __( 'Facebook', 'restaurant-brunch' ),
				'menu-item-url'    => 'https://www.facebook.com',
				'menu-item-status' => 'publish',
			) );
	
			wp_update_nav_menu_item( $restaurant_brunch_menu_id, 0, array(
				'menu-item-title'  => __( 'Twitter', 'restaurant-brunch' ),
				'menu-item-url'    => 'https://www.twitter.com',
				'menu-item-status' => 'publish',
			) );
	
			wp_update_nav_menu_item( $restaurant_brunch_menu_id, 0, array(
				'menu-item-title'  => __( 'Instagram', 'restaurant-brunch' ),
				'menu-item-url'    => 'https://www.instagram.com',
				'menu-item-status' => 'publish',
			) );
	
			if( !has_nav_menu( $restaurant_brunch_bpmenulocation ) ){
					$locations = get_theme_mod('nav_menu_locations');
					$locations[$restaurant_brunch_bpmenulocation] = $restaurant_brunch_menu_id;
					set_theme_mod( 'nav_menu_locations', $locations );
			}
		}
	}

	public function setup_widgets() {

		// Create a front page and assigned the template
		$restaurant_brunch_home_title = 'Home';
		$restaurant_brunch_home_check = get_page_by_title($restaurant_brunch_home_title);
		$restaurant_brunch_home = array(
			'post_type' => 'page',
			'post_title' => $restaurant_brunch_home_title,
			'post_status' => 'publish',
			'post_author' => 1,
			'post_slug' => 'home'
		);
		$restaurant_brunch_home_id = wp_insert_post($restaurant_brunch_home);

		//Set the static front page
		$restaurant_brunch_home = get_page_by_title( 'Home' );
		update_option( 'page_on_front', $restaurant_brunch_home->ID );
		update_option( 'show_on_front', 'page' );

		// Create a Women and assigned the template
		$restaurant_brunch_gallery_title = 'Pages';
		$restaurant_brunch_gallery_check = get_page_by_title($restaurant_brunch_gallery_title);
		$restaurant_brunch_gallery = array(
			'post_type' => 'page',
			'post_title' => $restaurant_brunch_gallery_title,
			'post_content' => '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',
			'post_status' => 'publish',
			'post_author' => 1,
			'post_slug' => 'pages'
		);
		$restaurant_brunch_gallery_id = wp_insert_post($restaurant_brunch_gallery);

		// Create a posts page and assigned the template
		$restaurant_brunch_blog_title = 'Shop';
		$restaurant_brunch_blog = get_page_by_title($restaurant_brunch_blog_title);

		if (!$restaurant_brunch_blog) {
			$restaurant_brunch_blog = array(
				'post_type' => 'page',
				'post_title' => $restaurant_brunch_blog_title,
				'post_status' => 'publish',
				'post_author' => 1,
				'post_slug' => 'shop'
			);
			$restaurant_brunch_blog_id = wp_insert_post($restaurant_brunch_blog);

			if (is_wp_error($restaurant_brunch_blog_id)) {
				// Handle error
			}
		} else {
			$restaurant_brunch_blog_id = $restaurant_brunch_blog->ID;
		}
		// Set the posts page
		update_option('page_for_posts', $restaurant_brunch_blog_id);

		// Create a Contact and assigned the template
		$restaurant_brunch_contact_title = 'About';
		$restaurant_brunch_contact_check = get_page_by_title($restaurant_brunch_contact_title);
		$restaurant_brunch_contact = array(
			'post_type' => 'page',
			'post_title' => $restaurant_brunch_contact_title,
			'post_content' => '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960 with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>',
			'post_status' => 'publish',
			'post_author' => 1,
			'post_slug' => 'about'
		);
		$restaurant_brunch_contact_id = wp_insert_post($restaurant_brunch_contact);

		// Create a posts page and assigned the template
		$restaurant_brunch_blog_title = 'Blogs';
		$restaurant_brunch_blog = get_page_by_title($restaurant_brunch_blog_title);

		if (!$restaurant_brunch_blog) {
			$restaurant_brunch_blog = array(
				'post_type' => 'page',
				'post_title' => $restaurant_brunch_blog_title,
				'post_status' => 'publish',
				'post_author' => 1,
				'post_slug' => 'blog'
			);
			$restaurant_brunch_blog_id = wp_insert_post($restaurant_brunch_blog);

			if (is_wp_error($restaurant_brunch_blog_id)) {
				// Handle error
			}
		} else {
			$restaurant_brunch_blog_id = $restaurant_brunch_blog->ID;
		}
		// Set the posts page
		update_option('page_for_posts', $restaurant_brunch_blog_id);


		/*----------------------------------------- Header Section --------------------------------------------------*/

		set_theme_mod( 'restaurant_brunch_enable_topbar', true);
		set_theme_mod( 'restaurant_brunch_enable_header_search_section', true);
		set_theme_mod( 'restaurant_brunch_enable_social', true);
		set_theme_mod( 'restaurant_brunch_discount_topbar_text', 'Sign up for 20% off on your order.');
		set_theme_mod( 'restaurant_brunch_discount_topbar_button_text', 'Subscribe.');
		set_theme_mod( 'restaurant_brunch_phone_number', '+91 1234 567 8910');

		// ------------------------- Banner Section -------------------------

		set_theme_mod( 'restaurant_brunch_enable_banner_section', true );
		set_theme_mod( 'restaurant_brunch_banner_button_label', 'Explore' );
		set_theme_mod( 'restaurant_brunch_banner_button_link', '#' );

		$restaurant_brunch_category_slider = get_term_by( 'name', 'Banner', 'category' );
		if ( ! $restaurant_brunch_category_slider ) {
			$restaurant_brunch_category_slider = wp_create_category( 'Banner' );
		} else {
			$restaurant_brunch_category_slider = $restaurant_brunch_category_slider->term_id;
		}

		// Set the theme mod with the Banner category ID
		set_theme_mod( 'restaurant_brunch_banner_slider_category', 'Banner' );

		// Post titles and banner image filenames
		$banner_post_titles = array(
			'Culinary Art on Every Plate',
			'Savor the Flavors of Tradition',
			'Fresh Ingredients, Authentic Taste'
		);

		$banner_images = array(
			'banner1.png',
			'banner2.png',
			'banner3.png'
		);

		// Path to theme images
		$banner_img_base_path = get_template_directory() . '/resource/img/';

		foreach ( $banner_post_titles as $i => $title ) {
			$post_exists = get_page_by_title( $title, OBJECT, 'post' );
			if ( $post_exists ) {
				continue;
			}

			$post_id = wp_insert_post( array(
				'post_title'    => $title,
				'post_content'  => 'Serving handcrafted meals in a cozy, welcoming atmosphere',
				'post_status'   => 'publish',
				'post_category' => array( $restaurant_brunch_category_slider ),
			) );

			$filename = $banner_images[$i];
			$file     = $banner_img_base_path . $filename;

			if ( file_exists( $file ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';

				$upload = wp_upload_bits( $filename, null, file_get_contents( $file ) );
				if ( ! $upload['error'] ) {
					$wp_filetype = wp_check_filetype( $upload['file'], null );
					$attachment  = array(
						'post_mime_type' => $wp_filetype['type'],
						'post_title'     => sanitize_file_name( $filename ),
						'post_status'    => 'inherit'
					);
					$attach_id  = wp_insert_attachment( $attachment, $upload['file'], $post_id );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
					wp_update_attachment_metadata( $attach_id, $attach_data );
					set_post_thumbnail( $post_id, $attach_id );
				}
			}
		}

		// ------------------------- Product Category Section -------------------------
		set_theme_mod( 'restaurant_brunch_enable_service_section', true );
		set_theme_mod( 'restaurant_brunch_trending_post_heading', 'Shop By Category' );

		$restaurant_brunch_categories = array(
			'Appetizers',
			'Main Course',
			'Desserts',
			'Beverages',
			'Starters',
			'Salads',
			'Specials'
		);

		foreach ( $restaurant_brunch_categories as $index => $cat_name ) {
			$term = wp_insert_term(
				$cat_name,
				'product_cat',
				array( 'slug' => sanitize_title( $cat_name ) )
			);

			if ( ! is_wp_error( $term ) ) {
				$cat_id = $term['term_id'];

				// === Category Image ===
				$category_image_path = get_template_directory() . '/resource/img/category' . ( $index + 1 ) . '.png';
				if ( file_exists( $category_image_path ) ) {
					require_once ABSPATH . 'wp-admin/includes/image.php';
					require_once ABSPATH . 'wp-admin/includes/file.php';
					require_once ABSPATH . 'wp-admin/includes/media.php';

					$filename = basename( $category_image_path );
					$upload   = wp_upload_bits( $filename, null, file_get_contents( $category_image_path ) );

					if ( ! $upload['error'] ) {
						$wp_filetype = wp_check_filetype( $upload['file'], null );
						$attachment  = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title'     => sanitize_file_name( $filename ),
							'post_status'    => 'inherit'
						);
						$attach_id  = wp_insert_attachment( $attachment, $upload['file'] );
						$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
						wp_update_attachment_metadata( $attach_id, $attach_data );

						update_term_meta( $cat_id, 'thumbnail_id', $attach_id );
					}
				}

				// === Create 4 products in this category ===
				for ( $p = 1; $p <= 4; $p++ ) {
					$product_name = $cat_name . ' Product ' . $p;

					$product_id = wp_insert_post( array(
						'post_title'   => $product_name,
						'post_content' => 'Demo description for ' . $product_name,
						'post_status'  => 'publish',
						'post_type'    => 'product',
					) );

					if ( $product_id ) {
						wp_set_object_terms( $product_id, (int) $cat_id, 'product_cat' );

						// === Use same image as category image ===
						if ( ! empty( $attach_id ) ) {
							set_post_thumbnail( $product_id, $attach_id );
						}

						// === Make it a simple product ===
						update_post_meta( $product_id, '_regular_price', '20' );
						update_post_meta( $product_id, '_price', '20' );
						update_post_meta( $product_id, '_stock_status', 'instock' );
					}
				}
			}
		}

		// ---------------------------------------- Related post_tag --------------------------------------------------- //	
		
		set_theme_mod('restaurant_brunch_post_related_post_label','Related Posts');
		set_theme_mod('restaurant_brunch_related_posts_count','3');

		$this->restaurant_brunch_customizer_primary_menu();
		$this->restaurant_brunch_customizer_socail_nav_menu();
	}
}