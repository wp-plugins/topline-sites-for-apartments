<?php

/**
 * Response Handler
 *
 * @link       http://30lines.com
 * @since      1.0.0
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/admin
 */

class Topline_Admin_Response {
	public $view;
	public $status;
	public $credentials;
	public $floorplans;
	public $property;
	public $company;
	public $user;
	public $action;
	public $method;
	public $extra;
	public $referer;

	public function __construct() {
		$this->method = $_SERVER['REQUEST_METHOD'];
		
		$refererURL = explode('/wp-admin/', $_SERVER["HTTP_REFERER"]);

		$this->referer = $refererURL[1];
	}

	public function __set($name, $value)
    {
        $this->$name = $value;
    }

	// public function setView($string) {
	// 	$this->view = $string;
	// }
	
	// public function setStatus($string) {
	// 	$this->status = $string;
	// }

	// public function setCredentials($array) {
	// 	$this->credentials = $array;
	// }

	// public function setFloorplans($array) {
	// 	$this->floorplans = $array;
	// }

	// public function setProperty($array) {
	// 	$this->property = $array;
	// }

	// public function setCompany($array) {
	// 	$this->comapany = $array;
	// }

	// public function setUser($mixed) {
	// 	$this->user = $mixed;
	// }

	public function display() {
		$responseProperties = get_object_vars($this);
		
		foreach($responseProperties as $key => $value) {
			if(isset($value)) {
				$$key = $value;
				$response[] = $key;
			}
		}
		return compact($response);
	}
}