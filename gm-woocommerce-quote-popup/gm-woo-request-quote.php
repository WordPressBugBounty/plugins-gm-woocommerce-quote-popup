<?php
/**
 * Plugin Name: Product Enquiry for WooCommerce
 * Description: Customer Can be ask quote of product via popup
 * Version:     3.2
 * Author:      Gravity Master
 * License:     GPLv2 or later
 * Text Domain: gmwqp
 */

/* Stop immediately if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/* All constants should be defined in this file. */
if ( ! defined( 'GMWQP_PREFIX' ) ) {
	define( 'GMWQP_PREFIX', 'gmwqp' );
}
if ( ! defined( 'GMWQP_PLUGIN_DIR' ) ) {
	define( 'GMWQP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'GMWQP_PLUGIN_BASENAME' ) ) {
	define( 'GMWQP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'GMWQP_PLUGIN_URL' ) ) {
	define( 'GMWQP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

/* Auto-load all the necessary classes. */
if( ! function_exists( 'gmwqp_class_auto_loader' ) ) {
	
	function gmwqp_class_auto_loader( $class ) {
		
		$includes = GMWQP_PLUGIN_DIR . 'includes/' . $class . '.php';
		
		if( is_file( $includes ) && ! class_exists( $class ) ) {
			include_once( $includes );
			return;
		}
		
	}
}
spl_autoload_register('gmwqp_class_auto_loader');

/* Initialize all modules now. */
new GMWQP_Cron();
new GMWQP_Shortcode();
new GMWQP_Comman();
new GMWQP_Admin();
new GMWQP_Frontend();
