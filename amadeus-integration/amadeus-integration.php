<?php
/**
 * Plugin Name: Amadeus Integration
 * Plugin URI: 
 * Description: 
 * Version: 1.0
 * Author: Stanislav Matrosov
 * Author URI: https://github.com/Matrosovdream/
*/

defined( 'ABSPATH' ) || exit;

if ( !defined( 'TDS_PLUGIN_FILE' ) ) {
	define( 'TDS_PLUGIN_FILE', __FILE__ );
}
define('PW_PLUGIN_DIR', plugin_dir_url( __FILE__ ));

// How to get images
// https://github.com/tsolakoua/amadeus-hotel-images-python

// Search city:
// https://developers.amadeus.com/self-service/category/destination-experiences/api-doc/city-search/api-reference


require_once('classes/api.class.php');
require_once('classes/places.class.php');
require_once('classes/monday.class.php');

require_once('shortcodes/hotel_search.php');
require_once('actions/actions.php');
require_once('actions/ajax.php');




