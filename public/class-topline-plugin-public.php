<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://30lines.com
 * @since      1.0.0
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/admin
 * @author     Your Name <email@example.com>
 */
class Topline_Plugin_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of the plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Topline_Plugin_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Topline_Plugin_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/topline-plugin-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Topline_Plugin_Public_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Topline_Plugin_Public_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/topline-plugin-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * @param $single_template
	 * @return string
	 */
	
	public function topline_load_template() {
	     global $post;

	     if ($post->post_type == 'thirty_lines_prop') {
	     	if(file_exists(locate_template('single-thirty_lines_prop.php'))) {
	        	$single_template = locate_template('single-thirty_lines_prop.php');
	        } else {
	        	// do we need a beginning path or can we just reference it by itself?
	        	$single_template = plugin_dir_path(__FILE__) . 'partials/topline-single-property.php';
	        }
	        
	     }

	     if ($post->post_type == 'thirty_lines_fp') {
	     	if(file_exists(locate_template('single-thirty_lines_fp.php'))) {
	        	$single_template = locate_template('single-thirty_lines_fp.php');
	        } else {
	        	// do we need a beginning path or can we just reference it by itself?
	        	$single_template = plugin_dir_path(__FILE__) . 'partials/topline-single-floorplan.php';
	        }
	        
	     }
	     
	     return $single_template;
	}

}
