<?php
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
 */
class Topline_Query {
	
	static function get_solo_floorplans() {
		$floorplan_args = array(
				'post_type' 		=> 'thirty_lines_fp',
				'posts_per_page' 	=> 200,
				'no_found_rows' 	=> true,
				'post_parent'		=> 0
			);

		$floorplans = new WP_Query($floorplan_args);
		
		return $floorplans;
	}

}

?>