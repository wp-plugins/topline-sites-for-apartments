<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://30lines.com
 * @since      1.0.0
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/includes
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/admin
 * @author     Eric Katz <eric@30lines.com>
 */
class Topline_Plugin_Admin {

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

	private $request;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $name       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $name, $version ) {

		$this->name = $name;
		$this->version = $version;

	}
	static function pull_feed($credentials) {
		$sync_rate = $credentials['sync_rate'];
		$apikey = $credentials['api_key'];
		$username = $credentials['user']['username'];

		$feed = false;
		if($username && $apikey) {
			$response = GuzzleHttp\post('http://topline.30lines.com/api/v1/' . $username, [
					'body' => [
						'api_key' => $apikey
					]
				]);
			$response = json_decode($response->getBody(), true);

			$propertyID = $response['data']['properties'][0]['id'];

			if(isset($propertyID)) {
				$response = GuzzleHttp\post('http://topline.30lines.com/api/v1/' . $username . '/property/' . $propertyID , [
						'body' => [
							'api_key' => $apikey
						]
					]);
				$response = json_decode($response->getBody(), true);

				// Store feed in transient for $sync_rate time.
				$feed = set_transient('topline_feed', $response, $sync_rate);
				
				if(isset($response['data'])) {

					// Add missing Floorplans
					$floorplans = $response['data']['floorplans'];
					self::add_new_floorplans($floorplans);

					// Update missing solo Property
					$property = get_option('topline_solo_property');
					if(!$property) {
						unset($response['data']['floorplans']);
						update_option('topline_solo_property', $response['data']);
					}
				}
			}
		}
		return $feed;
	}
	public function create_new_floorplan($floorplan) {

		$floorplan_post = [
			'post_type' => 'thirty_lines_fp',
			'post_title' => $floorplan['name'],
			'post_status' => 'publish'
		];
		$inserted_fp = wp_insert_post($floorplan_post);

		self::create_floorplan_meta($inserted_fp, $floorplan);

		return $inserted_fp;
	}
	public function create_floorplan_meta($inserted_fp, $floorplan) {
		$meta = [
			'fp_unitcount' => $floorplan['unitcount'],
			'fp_bedrooms' => $floorplan['bedrooms'],
			'fp_bathrooms' => $floorplan['bathrooms'],
			'fp_sqft_min' => $floorplan['squarefeet']['min'],
			'fp_sqft_max' => $floorplan['squarefeet']['max'],
			'fp_rent_min' => $floorplan['rent']['min'],
			'fp_rent_max' => $floorplan['rent']['max'],
			'fp_images' => serialize($floorplan['images']),
			'fp_visibility' => $floorplan['visibility'],
			'fp_available' => $floorplan['available'],
			'fp_unique_id' => $floorplan['id']
		];

		foreach($meta as $key => $value) {
			update_post_meta( $inserted_fp, $key, $value );
		}

	}
	static function add_new_floorplans($floorplans) {
		$fp_check = new WP_Query([
			'post_type' => 'thirty_lines_fp',
			'posts_per_page' => -1
		]);

		$fp_current_ids = [];
		
		if( $fp_check->have_posts() ) : while( $fp_check->have_posts() ) : $fp_check->the_post();
			
			$floorplan_unique_id = get_post_meta(get_the_ID(), 'fp_unique_id', true);
			$fp_current_ids[] = $floorplan_unique_id;

		endwhile; endif;

		foreach($floorplans['data'] as $floorplan) {
			
			if(!in_array($floorplan['id'], $fp_current_ids)) {
				
				$inserted_fp = self::create_new_floorplan($floorplan);

			}
		}
	}

	static function property() {

		$credentials = get_option('topline_credentials');
		
		$property = get_option('topline_solo_property');

		$feed = get_transient('topline_feed');

		if(!$feed) {
			$feed = self::pull_feed($credentials);
		}
		return $property;
	}

	static function floorplans() {

		$floorplans = Topline_Converter::floorplans( Topline_Query::get_solo_floorplans() );

		if($floorplans) {
			return $floorplans;
		}

		return false;
	}
	public function validate_credentials($request, Topline_Admin_Response $response) {

		

		if( ( $request['apikey'] == '') || ($request['username'] == '')  ) {
			$response->status = 'Either username or api key were invalid.';
			

			return $response->display();
		} 		
		


		$http =  GuzzleHttp\post('http://topline.30lines.com/check-api-credentials', [
			'body' => [
				'username' => $request['username'],
				'api_key' => $request['apikey']
			]
		]);

		$body = json_decode($http->getBody(), true);
		
		$response->status = $body['status'];
		$response->extra = $body;

		return $response;
	}
	public function attach_credentials($request, Topline_Admin_Response  $response) {
		delete_transient('topline_feed');
		delete_option('topline_install_notice');
		delete_option('topline_solo_property');
		
		$body = $response->extra;	

		$user = $body['user'];
		$user['avatar'] = $body['avatar'];
		$install_type = $body['install_type'];

		$newCredentials = [
			'user' => $user, 
			'api_key' => $request['apikey'], 
			'install_type' => $install_type, 
			'sync_rate' => 3600
		];

		update_option('topline_credentials', $newCredentials);
		
		$saveNewProperty = self::property();
		$response->credentials = $newCredentials;

		$response->view = $install_type;
		return $response;
	}
	public function handle_credentials($request, Topline_Admin_Response $response) {
		
		$response = self::validate_credentials($request, $response);

		if($response->status == 'success') {	
			$response = self::attach_credentials($request, $response);
		}

		return wp_redirect('options-general.php?page=topline-plugin&view=' . $response->view);
	}

	static function remove_credentials(Topline_Admin_Response $response, $credentials) {
		unset($credentials['user']);
		unset($credentials['api_key']);
		unset($credentials['sync_rate']);
		update_option('topline_credentials', $credentials);

		return wp_redirect($response->referer);
	}
	public function update_sync_rate($request, Topline_Admin_Response $response, $credentials) {
		$credentials['sync_rate'] = $request['sync_rate'];
		update_option('topline_credentials', $credentials);
		$response->view = 'settings';
		
		return wp_redirect('options-general.php?page=topline-plugin&view=' . $response->view);
	}
	static function handle_settings($request, Topline_Admin_Response $response, $credentials) {
		if(isset($request['install_type']) && ( $credentials['install_type'] != $request['install_type'] )) {
			$response->view = $request['install_type'];

			delete_option('topline_install_notice');
			
			update_option('topline_credentials', ['install_type' => $view]);
			return wp_redirect('options-general.php?page=topline-plugin&view=' . $response->view);
		} 
		if(isset($request['sync_rate'])) {
			$response = self::update_sync_rate($request, $response, $credentials);
		}

		return $response;
	}
	/**
	 * returns the correct view for admin
	 * @return Topline_Admin_Response
	 */
	static function handle_view($request, Topline_Admin_Response $response) {
		var_dump($response->method);
		// Need to refactor these if statements into named scenarios
		$credentials = get_option('topline_credentials');

		// If we are removing credentials		
		if($request['remove_credentials'] === 'true') {
			$credentials = self::remove_credentials($response, $credentials);
		}

		// Our handlers (controllers?)
		if($response->method == 'POST' && $request['view'] === 'settings') {
			return self::handle_settings($request, $response, $credentials);
		}

		if($response->method == 'POST' && $request['view'] === 'welcome') {
			return self::handle_credentials($request, $response);
		}

		$default = $request['view'] ?: 'settings';
		$default = isset($credentials['install_type']) ? $credentials['install_type'] : $default;
		$view = $credentials ? ($request['view']) ?: $default : 'settings';

		$user = false;
		$install_type = false;
		$status = false;



		if($credentials) {
			$user = $credentials['user'];
			$install_type = $credentials['install_type'];
		} // else {
		// 	update_option('topline_credentials', $credentials);
		// }

		$response = self::load_install_type($response, $install_type, $credentials);

		if($request['action']) {
			$action = $request['action'];
			$response->action = $action;
		}
		$response->view = $view;
		$response->status = $status;
		$response->credentials = $credentials;

		return $response->display();		
	}

	static function update_company($request) {
		$company = $request;
		unset($company['page']);
		unset($company['view']);
		
		update_option('topline_company', $company);

		return $company;
	}
	static function property_installation(Topline_Admin_Response $response) {
		$property = self::property();
		$floorplans = self::floorplans();
		$response->property = $property;
		$response->floorplans = $floorplans;

		return $response;
	}
	static function company_installation(Topline_Admin_Response $response) {
		$company = get_option('topline_company');
		

		$response->company = $company;

		return $response;
	}
	static function load_install_type(Topline_Admin_Response $response, $install_type, $credentials) {
		$user = $credentials['user'];

		if($install_type === 'property') {
			$response = self::property_installation($response);
		}

		if($install_type === 'company') {
			$response = self::company_installation($response);
		}

		return $response;
	}


	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Topline_Plugin_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Topline_Plugin_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->name, plugin_dir_url( __FILE__ ) . 'css/topline-plugin-admin.css', [], $this->version, 'all' );
		wp_enqueue_style( $this->name . '-dataTables-css', plugin_dir_url( __FILE__ ) . 'library/dataTables/dataTables.bootstrap.css', [], $this->version, 'all' );
		wp_enqueue_style( 'mapbox_css', 'https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.css', [], '2.1.2', 'all' );
		wp_enqueue_style( 'fontawesome-420', '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css', [], '4.2.0', 'all');

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Topline_Plugin_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Topline_Plugin_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->name, plugin_dir_url( __FILE__ ) . 'js/topline-plugin-admin.js', [ 'jquery' ], $this->version, false );
		wp_enqueue_script( $this->name . '-dataTables-jquery', plugin_dir_url( __FILE__ ) . 'library/dataTables/jquery.dataTables.js', [ 'jquery' ], $this->version, false );
		wp_enqueue_script( $this->name . '-dataTables-boot', plugin_dir_url( __FILE__ ) . 'library/dataTables/dataTables.bootstrap.js', [ 'jquery' , $this->name . '-dataTables-jquery' ], $this->version, false );
		wp_enqueue_script( 'mapbox_js', 'https://api.tiles.mapbox.com/mapbox.js/v2.1.2/mapbox.js', [], '2.1.2', 'all' );
		wp_enqueue_script( $this->name . '-bootstrap', plugin_dir_url( __FILE__ ) . 'js/bootstrap.min.js', [ 'jquery' ], $this->version, false );

	}

	/**
	 * Add Topline admin page to wordpress Admin
	 * 
	 * @since 1.0.0
	 */
	public function load_options_page() {
		$this->request = self::handle_view($_REQUEST, new Topline_Admin_Response);
		add_options_page( 'TopLine', 'TopLine', 'manage_options', 'topline-plugin', [ $this, 'admin_display' ] );
	}

	public function load_property_post_type() {
		register_post_type( 'thirty_lines_prop', [
			'labels'			=> [
			    'name' => __('Properties', 'post type general name'),
				'singular_name' => __('Property', 'post type singular name'),
				'add_new' => __('Add New', 'apartment'),
				'add_new_item' => __('Add New Property'),
				'edit_item' => __('Edit Property'),
				'new_item' => __('New Property'),
				'view_item' => __('View Property'),
				'search_items' => __('Search Properties'),
				'not_found' =>  __('Nothing found'),
				'not_found_in_trash' => __('Nothing found in Trash'),
				'parent_item_colon' => ''
			],
			'capability_type'	=> 'page',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => false,
			'query_var' => true,
			'rewrite' => [ 'slug' => 'apartment' ],
			'capability_type' => 'post',
			'hierarchical' => false,
			'menu_position' => 27,
//			'menu_icon' => topline_get_dir('/assets/images/apartments-icon.png'),
			'supports' => [ 'title','thumbnail','excerpt','editor','revisions' ]
		]);
		flush_rewrite_rules();
	}

	public function load_floorplan_post_type() {
		register_post_type( 'thirty_lines_fp', [
			'labels'			=> [
			    'name' => __('Floor Plans', 'post type general name'),
				'singular_name' => __('Floor Plan', 'post type singular name'),
				'add_new' => __('Add New', 'floorplan'),
				'add_new_item' => __('Add New Floor Plan'),
				'edit_item' => __('Edit Floor Plan'),
				'new_item' => __('New Floor Plan'),
				'view_item' => __('View Floor Plan'),
				'search_items' => __('Search Floor Plans'),
				'not_found' =>  __('Nothing found'),
				'not_found_in_trash' => __('Nothing found in Trash'),
				'parent_item_colon' => ''
			],
			'capability_type'	=> 'page',
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => false,
			'query_var' => true,
			'rewrite' => [ 'slug' => 'floor-plan' ],
			'capability_type' => 'post',
			'hierarchical' => true,
//			'menu_icon' => topline_get_dir('/assets/images/apartments-icon.png'),
			'supports' => [ 'title','thumbnail' ]
		]);
	}

	public function admin_display() {
		$request = $this->request;
		require('layout/topline-plugin-admin-display.php');
	}
}
