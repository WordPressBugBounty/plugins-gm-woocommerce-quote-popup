<?php
/**
 * Plugin Name:       Product Enquiry for WooCommerce
 * Plugin URI:        https://www.codesmade.com/product-enquiry-for-woocommerce-documentation/
 * Description:       Add a Request a Quote / Product Enquiry button to WooCommerce products. Customers ask via a popup form; you get the enquiry by email and in your dashboard.
 * Version:           3.2.1
 * Requires at least: 5.8
 * Requires PHP:      7.2
 * Requires Plugins:  woocommerce
 * Author:            Gravity Master
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       gm-woocommerce-quote-popup
 */

/* Stop immediately if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/* The Lite and Pro versions share class names, so only one of them may run at a time. */
if ( defined( 'GMWQP_PLUGIN_DIR' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="notice notice-warning"><p><strong>Product Enquiry for WooCommerce:</strong> '
			. esc_html__( 'Another version (Lite or Pro) of this plugin is already active. Please deactivate one of them.', 'gm-woocommerce-quote-popup' )
			. '</p></div>';
	} );
	return;
}

define( 'GMWQP_PREFIX', 'gmwqp' );
define( 'GMWQP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GMWQP_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'GMWQP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GMWQP_IS_LITE', true );
define( 'GMWQP_PRO_URL', 'https://www.codesmade.com/store/product-enquiry-for-woocommerce-pro/' );

/* Auto-load all the necessary classes. */
if ( ! function_exists( 'gmwqp_class_auto_loader' ) ) {
	function gmwqp_class_auto_loader( $class ) {
		$includes = GMWQP_PLUGIN_DIR . 'includes/' . $class . '.php';
		if ( is_file( $includes ) && ! class_exists( $class ) ) {
			include_once( $includes );
		}
	}
}
spl_autoload_register( 'gmwqp_class_auto_loader' );

/* Initialize all modules now. */
new GMWQP_Cron();
new GMWQP_Shortcode();
new GMWQP_Comman();
new GMWQP_Admin();
new GMWQP_Frontend();
new GMWQP_API();

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'gmwqp_action_links' );
if ( ! function_exists( 'gmwqp_action_links' ) ) {
	function gmwqp_action_links( $links ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'admin.php?page=GMWQP' ) ) . '">' . esc_html__( 'Settings', 'gm-woocommerce-quote-popup' ) . '</a>' );
		$links[] = '<a href="' . esc_url( GMWQP_PRO_URL ) . '" target="_blank" rel="noopener">' . esc_html__( 'Go Pro', 'gm-woocommerce-quote-popup' ) . '</a>';
		return $links;
	}
}
