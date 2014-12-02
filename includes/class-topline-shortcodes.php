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
class Topline_Shortcodes {


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

		

	}

	public function load_shortcodes() {
		// $this->load_property();
		$this->load_floorplans();
		$this->load_search();
		$this->load_communities();
		$this->load_property_info();
	}

	public function load_property() {

	}

	public function load_floorplans() {

		wp_enqueue_style('topline-floorplan', plugins_url() . '/topline/admin/css/topline-floorplan.css', array(), '1.0.0' );

		global $post; 

		$credentials = get_option('topline_credentials');
		$install_type = $credentials['install_type'];
		if($install_type === 'property') {
			$floorplans = Topline_Query::get_solo_floorplans();
		}


		$floorplan_content = '<div class="topline floorplans">';
		
		if( $floorplans->have_posts() ) : while( $floorplans->have_posts() ) : $floorplans->the_post();
			
			$visibility = get_post_meta($post->ID, 'fp_visibility', true);

			if($visibility === 'visible') {
				$floorplan_content .= '<div class="topline floor-plan-overview">'; 
				$floorplan_data = get_post_meta($post->ID);
				
				$floorplan_content .= '<h2>' . get_the_title($post->ID) . '</h2>'; 

				$floorplan_content .= '<ul class="topline floorplan plan-' . $floorplan_data['fp_unique_id'][0] . '">';
				$floorplan_content .= 	'<li class="topline floorplan-info bedrooms">' . $floorplan_data['fp_bedrooms'][0] . ' Bed</li>';
				$floorplan_content .= 	'<li class="topline floorplan-info bathrooms">' . $floorplan_data['fp_bathrooms'][0] . ' Bath</li>';

				if($floorplan_data['fp_sqft_min'][0] === $floorplan_data['fp_sqft_max'][0] ) {
					$floorplan_content .= 	'<li class="topline floorplan-info sqft-range">Square Feet: ' . $floorplan_data['fp_sqft_min'][0] . '</li>';
				} else {
					$floorplan_content .= 	'<li class="topline floorplan-info sqft-range">Square Feet: ' . $floorplan_data['fp_sqft_min'][0] . ' - ' . $floorplan_data['fp_sqft_max'][0] . '</li>';
				}

				if($floorplan_data['fp_rent_min'][0] === $floorplan_data['fp_rent_max'][0]) {
					$floorplan_content .= 	'<li class="topline floorplan-info rent-range">Rent: $' . $floorplan_data['fp_rent_min'][0] . '</li>';
				} else {
					$floorplan_content .= 	'<li class="topline floorplan-info rent-range">Rent: $' . $floorplan_data['fp_rent_min'][0] . ' - ' . $floorplan_data['fp_rent_max'][0] . '</li>';
				}
				
				$availability = $floorplan_data['fp_available'][0];
				if($availability != '' && $availability != '1') {
					$floorplan_content .= 	'<li class="topline floorplan-info availability date-available">' . $floorplan_data['fp_available'][0] . '</li>';
				} elseif($availability === '1') {
					$floorplan_content .= 	'<li class="topline floorplan-info availability available">Currently Available</li>';
				} else {
					$floorplan_content .= 	'<li class="topline floorplan-info availability not-available">Not Available</li>';
				}
				
				$floorplan_content .= '</ul>';

				$floorplan_images = get_post_meta($post->ID, 'fp_images', true);
				$floorplan_images = unserialize($floorplan_images);

				$floorplan_content .= '<ul class="topline floor-plan-images">';
				foreach($floorplan_images as $image_src) {
					$floorplan_content .= 	'<li class="topline image"><a href="' . get_permalink() . '"><img src="' . $image_src . '" /></a></li>';
				}
				$floorplan_content .= '</ul>';
				
				$floorplan_content .= '</div>';
			}


			
		endwhile; endif;

		$floorplan_content .= '</div>';

		return $floorplan_content;

		//return 'floorplans';
	}

	public function load_search() {
		return 'search';
	}

	public function load_communities() {

		$credentials = get_option('topline_credentials');
		$install_type = $credentials['install_type'];
		
		if($install_type != 'property') {
			$property_args = array(
					'post_type' => 'thirty_lines_prop',
					'posts_per_page' => '200'
				);

		} else {
			$property_notice = 'Sorry, looks like you only have one property. Did you mean to use the &lbrack;property-info&rbrack; shortcode?';
		}

		$properties = new WP_Query($property_args);

		$property_list = '<div class="topline property-list">';
		$property_list .= '<ul class="properties">';

		if( $properties->have_posts() ) : while( $properties->have_posts() ) : $properties->the_post();
			
			$property_list .= '<li class="property">';
			$property_list .= 	'<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
			$property_list .= '</li>';

		endwhile; endif;

		$property_list .= '</ul>';
		$property_list .= '</div>';

		if($property_notice) {
			return $property_notice;
		} else {
			return $property_list;
		}
		return 'communities';
	}

	public function load_property_info() {

		if($show_map != false) {
			wp_enqueue_script( 'mapbox_js', 'https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.js', array(), '2.1.2', 'all' );
			wp_enqueue_style( 'mapbox_css', 'https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.css', array(), '2.1.2', 'all' );
		}
		
		$property = get_option('topline_solo_property');

		$social_media = array(
				'Facebook' 	=> $property['social']['facebook'], 
				'Twitter' 	=> $property['social']['twitter'], 
				'Instagram' => $property['social']['instagram'],
		);

		$property_info 	 = '<div class="topline property-info" itemscope itemtype="http://schema.org/ApartmentComplex">';
		$property_info 	.= '<h2 itemprop="name">' . $property['name'] . '</h2>';
		$property_info 	.= '<p itemprop="description">' . $property['description'] . '</p>';
		$property_info 	.= '<p itemprop="address">
								<span itemscope itemtype="http://schema.org/PostalAddress">
									<span itemprop="streetAddress">' . $property['address']['address_line1'] . '</span><br />
									<span itemprop="addressLocality">' . $property['address']['city'] . '</span>,
									<span itemprop="addressRegion">' . $property['address']['state'] . '</span>
									<span itemprop="postalCode">' . $property['address']['zip'] . '</span>
								</span>
							<p>';
		$property_info 	.= '<div itemprop="geo">
							    <span itemscope itemtype="http://schema.org/GeoCoordinates">
							      <meta itemprop="latitude" content="' . $property['latitude'] . '" />
							      <meta itemprop="longitude" content="' . $property['longitude'] . '" />
							    </span>
							</div>';
		$property_info  .= '<h3>Contact Us</h3><hr />';
		$property_info  .= '<p>Phone: <a href="tel:' . preg_replace('/\D+/', '', $property['phone']) . '" itemprop="telephone">' . $property['phone'] . '</a><br />';
		//$property_info  .= 'Website: <span itemprop="telephone">' . $property['website'] . '</span><br />';	
		$property_info  .= 'Email: <a href="mailto:' . $property['email'] . '">' . $property['email'] . '</a></p>';		

		/*  Make sure that there is something in the social array, 
			if so then start the list, add a list item for each that 
			doesn't have a blank URL and then close the list. */

		if( !count( array_filter( $social_media)) == 0 ) {
			$property_info .= '<h3>Connect With Us</h3>';
			$property_info .= '<ul class="topline social-media">';
			foreach( $social_media as $network=>$url ) {
				if( $url ) {
					$property_info .= '<li><a href="' . $url . '">' . $network . '</a></li>';
				}	
			}
			$property_info .= '</ul>';
		}

		$property_info 	.= '</div>'; // close out the main topline div

		return $property_info;

	}



}
