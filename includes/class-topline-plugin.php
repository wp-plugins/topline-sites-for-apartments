<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/includes
 * @author     Your Name <email@example.com>
 */
class Topline_Plugin {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Topline_Plugin_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $topline_plugin    The string used to uniquely identify this plugin.
	 */
	protected $topline_plugin;

	protected $install_type;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->topline_plugin = 'topline-plugin';
		$this->version = '1.0.0';

		$credentials = get_option('topline_credentials');
		
		if($credentials) {
			$user = $credentials['user'];
			$this->install_type = $credentials['install_type'];
		}

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Topline_Plugin_Loader. Orchestrates the hooks of the plugin.
	 * - Topline_Plugin_i18n. Defines internationalization functionality.
	 * - Topline_Plugin_Admin. Defines all hooks for the dashboard.
	 * - Topline_Plugin_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-topline-plugin-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-topline-plugin-i18n.php';

		/**
		 * The class responsible for defining response data for admin views.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-topline-plugin-response.php';

		/**
		 * The class responsible for defining all actions that occur in the Dashboard.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-topline-plugin-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-topline-plugin-public.php';

		$this->loader = new Topline_Plugin_Loader();


	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Topline_Plugin_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Topline_Plugin_i18n();
		$plugin_i18n->set_domain( $this->get_topline_plugin() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Topline_Plugin_Admin( $this->get_topline_plugin(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'load_options_page' );

		if($this->install_type == 'company') {
			$this->loader->add_action( 'init', $plugin_admin, 'load_property_post_type' );
		}
		
		$this->loader->add_action( 'init', $plugin_admin, 'load_floorplan_post_type' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Topline_Plugin_Public( $this->get_topline_plugin(), $this->get_version() );
		

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'single_template', $plugin_public, 'topline_load_template');

	}

	private function define_shortcodes() {

		$shortcodes	   = new Topline_Shortcodes();
		// var_dump($shortcodes->load_floorplans());
		// exit;
		add_shortcode('floorplans', array($shortcodes, 'load_floorplans'));
		add_shortcode('property', array($shortcodes, 'load_property'));
		add_shortcode('communities', array($shortcodes, 'load_communities'));
		add_shortcode('property-search', array($shortcodes, 'load_search'));
		add_shortcode('property-info', array($shortcodes, 'load_property_info'));
	
	}



	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_topline_plugin() {
		return $this->topline_plugin;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Topline_Plugin_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
