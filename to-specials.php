<?php
/*
 * Plugin Name: LSX Tour Operator Special Offers
 * Plugin URI:  https://www.lsdev.biz/product/tour-operator-special-offers/
 * Description: The Tour Operator Special Offers extension gives you the ability to create time-sensitive special prices that can be applied to Tour Operator post types you are using: Accommodations, destinations and/or tours.
 * Version:     1.3.6
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
define('LSX_TO_SPECIALS_VER',  '1.3.6' );


/* ======================= Below is the Plugin Class init ========================= */

require_once( LSX_TO_SPECIALS_PATH . '/classes/class-to-specials.php' );
