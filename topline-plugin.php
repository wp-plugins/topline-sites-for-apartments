<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * Dashboard. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://30lines.com/topline
 * @since             1.0.0
 * @package           Topline_Plugin
 *
 * @wordpress-plugin
 * Plugin Name:       TopLine Sites for Apartments
 * Plugin URI:        http://30lines.com/topline
 * Description:       Extendable plugin that helps you market your apartments and rental properties.
 * Version:           1.0.0
 * Author:            30Lines
 * Author URI:        http://30lines.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       topline-plugin
 * Domain Path:       /languages
 */
if (version_compare(PHP_VERSION, '5.4.0', '<')) {

	add_action('admin_notices', function() {

	echo "	<p class='update-nag'>
				TopLine Sites is made for WordPress installations running <strong>PHP 5.4.0</strong> or greater. You are currently running: <strong>". PHP_VERSION ."</strong>.
			</p>";

	});
	die;

}

// For composer dependencies
require 'vendor/autoload.php';

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * The code that runs during plugin activation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-topline-plugin-activator.php';

/**
 * The code that runs during plugin deactivation.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-topline-plugin-deactivator.php';

/** This action is documented in includes/class-topline-plugin-activator.php */
register_activation_hook( __FILE__, array( 'Topline_Plugin_Activator', 'activate' ) );

add_action('admin_init', function() {
	
	if (get_option('topline_activation_redirect', false)) {
		
		delete_option('topline_activation_redirect');
		
		if(!isset($_GET['activate-multi'])) {
			wp_redirect('options-general.php?page=topline-plugin&view=welcome');
		}

	}

});

add_action('admin_notices', function() {

	if (get_option('topline_install_notice', false)) {
		$screen = get_current_screen();
		if($screen->base != 'settings_page_topline-plugin') {
			$welcomeUrl = admin_url('options-general.php?page=topline-plugin&view=welcome');
	    	echo "	<p class='update-nag'>
						You still need to configure Topline, in order for it to be installed correctly. You can <a href='$welcomeUrl'>begin setup here</a>.
					</p>";
		}
	}

});

/** This action is documented in includes/class-topline-plugin-deactivator.php */
register_deactivation_hook( __FILE__, array( 'Topline_Plugin_Deactivator', 'deactivate' ) );

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/helpers.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/class-topline-converter.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/class-topline-query.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/class-topline-shortcodes.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/class-topline-plugin.php';




/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_topline_plugin() {

	$plugin = new Topline_Plugin();
	$plugin->run();

}
run_topline_plugin();