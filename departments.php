<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              
 * @since             1.0.0
 * @package           departments
 *
 * @wordpress-plugin
 * Plugin Name:       departments
 * Plugin URI:        
 * Description:       departments
 * Version:           1.0.0
 * Author:            
 * Author URI:        
 * License:           
 * License URI:       
 * Text Domain:       
 * Domain Path:       
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/** Start code HERE **/
define('DEPARTMENTS_PLUGIN_NAME', 'departments');
define('DEPARTMENTS_POST_TYPE', 'department');
define('DEPARTMENTS_VERSION', '0.0.1');
define('DEPARTMENTS_URL', plugins_url('', __FILE__));
define('DEPARTMENTS_PATH', plugin_dir_path( __FILE__ ));
define('DEPARTMENTS_DIR', basename(dirname(__FILE__)));
define('DEPARTMENTS_TXT_DOMAIN', 'departments');
define('GOOGLE_API_KEY','AIzaSyByx6q61twWq8bjZyR78AndRVurzFpiqQQ');

function load_translations()
{
	load_plugin_textdomain(
    	DEPARTMENTS_TXT_DOMAIN,
    	false,
    	DEPARTMENTS_DIR . '/languages/'
	);
}


require_once('exceptions/NotFoundException.php');
require_once('classes/Model.php');
require_once('classes/View.php');
require_once('department_post_type.php');
require_once('departments_frontend.php');
require_once("templates.php");
require_once("admin_panel.php");

add_action('plugins_loaded', 'load_translations');


