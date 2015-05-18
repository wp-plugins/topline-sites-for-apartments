<?php


/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/includes
 * @author     Your Name <email@example.com>
 */
class Topline_Plugin_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	/*  public static function activate() {
		if(!get_option('topline_credentials')) {
			add_option('topline_install_notice', true);
			add_option('topline_activation_redirect', true);
		}

		$site 		=	get_bloginfo('name');
		$url 		=	get_bloginfo('wpurl'); 
		$email 		=	get_bloginfo('admin_email'); 
		$version 	=	get_bloginfo('version');

		$response = GuzzleHttp\post('http://topline.30lines.com/topline-installed', [
			'body' => [
				'site' => $site,'wpurl' => $url,'email' => $email,'version' => $version
			]
		]);
	} */
}
