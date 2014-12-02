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
class Topline_Converter {
	
	static function floorplans(WP_Query $floorplans) {
		global $post;
		if( $floorplans->have_posts() ) : while( $floorplans->have_posts() ) : $floorplans->the_post();

			$fp_unitcount = get_post_meta($post->ID, 'fp_unitcount', true);
			$fp_bedrooms = get_post_meta($post->ID, 'fp_bedrooms', true);
			$fp_bathrooms = get_post_meta($post->ID, 'fp_bathrooms', true);
			$fp_sqft_min = get_post_meta($post->ID, 'fp_sqft_min', true);
			$fp_sqft_max = get_post_meta($post->ID, 'fp_sqft_max', true);
			$fp_rent_min = get_post_meta($post->ID, 'fp_rent_min', true);
			$fp_rent_max = get_post_meta($post->ID, 'fp_rent_max', true);
			$fp_images = get_post_meta($post->ID, 'fp_images', true);
			$fp_visibility = get_post_meta($post->ID, 'fp_visibility', true);
			$fp_available = get_post_meta($post->ID, 'fp_available', true);
			$fp_unique_id = get_post_meta($post->ID, 'fp_unique_id', true);

			$build[] = [
				'id' 		=> $fp_unique_id,
				'wpid'		=> $post->ID,
				'name' 		=> get_the_title(),
				'unitcount'	=> $fp_unitcount ?: null,
				'bedrooms' 	=> $fp_bedrooms,
				'bathrooms'	=> $fp_bathrooms,
				'squarefeet' => [
					'min' => $fp_sqft_min,
					'max' => $fp_sqft_max,
				],
				'rent' => [
					'min' => $fp_rent_min,
					'max' => $fp_rent_max,
				],
				'images' => unserialize($fp_images),
				'visibility'	=> $fp_visibility,
				'available' 	=> $fp_available
			];
			
		endwhile; endif;

		wp_reset_postdata();

		$floorplans = $build ?: $floorplans;
		return $floorplans;
	}

}