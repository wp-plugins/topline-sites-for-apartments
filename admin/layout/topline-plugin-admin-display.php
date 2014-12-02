<?php

/**
 * Provide a dashboard layout for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://30lines.com
 * @since      1.0.0
 *
 * @package    Topline_Plugin
 * @subpackage Topline_Plugin/admin/layout
 */

// var_dump($_REQUEST);
// var_dump($request);

/**
 * $request
 * @array returns with necessary attributes for view handling
 */

/**
 * alias block for $request array keys
 * @var variable
 */
$view = $request['view'];
$status = $request['status'];
$user = $request['credentials']['user'];
$credentials = $request['credentials'];
$property = $request['property'];
$floorplans = $request['floorplans'];
$company = $request['company'];


/**
 * Load partials and render view
 */
require('partials/top-bar.php'); 
require('views/' . $view .'.php'); ?>


