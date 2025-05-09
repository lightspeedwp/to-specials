<?php
/*
 * Plugin Name: Tour Operator Special Offers
 * Plugin URI:  https://touroperator.solutions/plugins/specials/
 * Description: The Tour Operator Special Offers extension gives you the ability to create time-sensitive special prices that can be applied to Tour Operator post types you are using: Accommodations, destinations and/or tours.
 * Version:     2.0.0
 * Author:      LightSpeed
 * Author URI:  https://lightspeedwp.agency/
 * License:     GPL3+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: to-specials
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('LSX_TO_SPECIALS_PATH',  plugin_dir_path( __FILE__ ) );
define('LSX_TO_SPECIALS_CORE',  __FILE__ );
define('LSX_TO_SPECIALS_URL',  plugin_dir_url( __FILE__ ) );
define('LSX_TO_SPECIALS_VER',  '2.0.0' );


/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_TO_SPECIALS_PATH . '/classes/class-to-specials.php' );
