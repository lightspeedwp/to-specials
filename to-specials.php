<?php
/*
 * Plugin Name: Tour Operator Special Offers
 * Plugin URI:  https://www.lsdev.biz/product/tour-operator-special-offers/
 * Description: The Tour Operator Special Offers extension gives you the ability to create time-sensitive special prices that can be applied to Tour Operator post types you are using: Accommodations, destinations and/or tours. 
 * Version:     1.1.0
 * Author:      LightSpeed
 * Author URI:  https://www.lsdev.biz/
 * License:     GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: lsx-specials
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LSX_TO_SPECIALS_PATH',  plugin_dir_path( __FILE__ ) );
define('LSX_TO_SPECIALS_CORE',  __FILE__ );
define('LSX_TO_SPECIALS_URL',  plugin_dir_url( __FILE__ ) );
define('LSX_TO_SPECIALS_VER',  '1.1.0' );

/**
 * Runs once when the plugin is activated.
 */
function lsx_to_specials_activate_plugin() {
    $lsx_to_password = get_option('lsx_api_instance',false);
    if(false === $lsx_to_password){
    	update_option('lsx_api_instance',LSX_API_Manager::generatePassword());
    }
}
register_activation_hook( __FILE__, 'lsx_to_specials_activate_plugin' );

/* ======================= The API Classes ========================= */
if(!class_exists('LSX_API_Manager')){
	require_once('classes/class-lsx-api-manager.php');
}

/** 
 *	Grabs the email and api key from the LSX Search Settings.
 */ 
function lsx_to_specials_options_pages_filter($pages){
	$pages[] = 'lsx-to-settings';
	return $pages;
}
add_filter('lsx_api_manager_options_pages','lsx_to_specials_options_pages_filter',10,1);

function lsx_to_specials_api_admin_init(){
	$options = get_option('_lsx-to_settings',false);
	$data = array('api_key'=>'','email'=>'');

	if(false !== $options && isset($options['api'])){
		if(isset($options['api']['to-specials_api_key']) && '' !== $options['api']['to-specials_api_key']){
			$data['api_key'] = $options['api']['to-specials_api_key'];
		}
		if(isset($options['api']['to-specials_email']) && '' !== $options['api']['to-specials_email']){
			$data['email'] = $options['api']['to-specials_email'];
		}		
	}

	$instance = get_option( 'lsx_api_instance', false );
	if(false === $instance){
		$instance = LSX_API_Manager::generatePassword();
	}

	$api_array = array(
		'product_id'	=>		'TO Specials',
		'version'		=>		'1.1.0',
		'instance'		=>		$instance,
		'email'			=>		$data['email'],
		'api_key'		=>		$data['api_key'],
		'file'			=>		'to-specials.php',
		'documentation' =>		'tour-operator-special-offers'
	);
	
	$lsx_to_api_manager = new LSX_API_Manager($api_array);
}
add_action('admin_init','lsx_to_specials_api_admin_init');

/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_TO_SPECIALS_PATH . '/classes/class-to-specials.php' );