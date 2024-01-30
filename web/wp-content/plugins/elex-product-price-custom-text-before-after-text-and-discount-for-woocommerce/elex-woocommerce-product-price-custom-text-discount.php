<?php
/*
Plugin name: ELEX WooCommerce Product Price Custom Text (Before & After Text) and Discount
Plugin URI: https://elextensions.com/plugin
Description: The plugin simplifies the task to add a text before and after the product price both globally and individually.It also allows you to apply a quick discount for your products.
Version: 4.0.1
WC requires at least: 2.6.0
WC tested up to: 8.4
Author: ELEXtensions
Author URI: https://elextensions.com/
Text Domain: elex-product-price-custom-text-and-discount
*/

// to check whether accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * To check whether woocommerce is activated
 *
 * @since 1.0.0
*/
if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once  ABSPATH . 'wp-admin/includes/plugin.php';
}
if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	deactivate_plugins( plugin_basename( __FILE__ ) );
	wp_die( '<b>WooCommerce</b> plugin must be active for <b>ELEX Product Price Custom Text (Before & After Text) and Discount Plugin</b> to work.' );
}

$ppct_plugins        = array(
	'elex-product-price-custom-text-before-after-text-and-discount-for-woocommerce/elex-woocommerce-product-price-custom-text-discount.php'       => "Basic Version of Elex Product Price Custom Text Before After Text And Discount Plugin is installed & activated. Please deactivate the Basic Version of Elex Product Price Custom Text Before After Text And Discount before activating PREMIUM version.<br>Don't worry! Your data will be retained.<br>Go back to <a href='" . esc_html( admin_url( 'plugins.php' ) ) . "'>plugins page</a>",
	'elex-before-after-text-premium/elex-product-price-custom-text-discount-premium.php' => "Premium Version of Elex Product Price Custom Text Before After Text And Discount Plugin is installed & activated. Please deactivate the Premium Version of Elex Product Price Custom Text Before After Text And Discount before activating Basic version.<br>Don't worry! Your data will be retained.<br>Go back to <a href='" . esc_html( admin_url( 'plugins.php' ) ) . "'>plugins page</a>",
);
$current_ppct_plugin = plugin_basename( __FILE__ );
foreach ( $ppct_plugins as $ppct_plugin => $error_msg ) {
	if ( $current_ppct_plugin === $ppct_plugin ) {
		continue;
	}

	if ( is_plugin_active( $ppct_plugin ) ) {
		deactivate_plugins( $current_ppct_plugin );
		wp_die( wp_kses_post( $error_msg ) );
	}
}

if ( ! defined( 'ELEX_PPCT_MAIN_PATH' ) ) {
	define( 'ELEX_PPCT_MAIN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ELEX_PPCT_MAIN_VIEWS' ) ) {
	define( 'ELEX_PPCT_MAIN_VIEWS', ELEX_PPCT_MAIN_PATH . 'views/' );
}

if ( ! defined( 'ELEX_PPCT_MAIN_IMG' ) ) {
	define( 'ELEX_PPCT_MAIN_IMG', plugin_dir_url( __FILE__ ) . 'assets/images/' );
}

require_once __DIR__ . '/vendor/autoload.php';
require 'includes/elex-ppct-woocmmerce-variation-settings.php';
require_once  ELEX_PPCT_MAIN_PATH . 'includes/class-ppct-init-handler.php' ;
$elex_ppct = new ELEX\PPCT\ELEX_PPCT_Init_Handler();
$elex_ppct->with_basename( plugin_basename( __FILE__ ) );
$elex_ppct->boot();

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'eh_ppct_basic_action_links' );
function eh_ppct_basic_action_links( $links ) {
	$setting_link = admin_url( 'admin.php?page=elex_product_price_custom_text_and_discount' );
	$plugin_links = array(
		'<a href="' . $setting_link . '">' . __( 'Settings', 'elex-product-price-custom-text-and-discount' ) . '</a>',
		'<a href="https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-product-price-custom-text-before-after-text-and-discount-plugin/" target="_blank">' . __( 'Documentation', 'elex-product-price-custom-text-and-discount' ) . '</a>',
		'<a href="https://elextensions.com/support/" target="_blank">' . __( 'Support', 'elex-product-price-custom-text-and-discount' ) . '</a>',
	);
	return array_merge( $plugin_links, $links );
}


// review component
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once  ABSPATH . 'wp-admin/includes/plugin.php';
}
include_once __DIR__ . '/review_and_troubleshoot_notify/review-and-troubleshoot-notify-class.php';
$data                      = get_plugin_data( __FILE__ );
$data['name']              = $data['Name'];
$data['basename']          = plugin_basename( __FILE__ );
$data['rating_url']        = 'https://elextensions.com/plugin/elex-woocommerce-product-price-custom-text-before-after-text-and-discount-plugin-free/#reviews';
$data['documentation_url'] = 'https://elextensions.com/knowledge-base/how-to-set-up-elex-woocommerce-product-price-custom-text-before-after-text-and-discount-plugin/';
$data['support_url']       = 'https://support.elextensions.com/';

new \Elex_Review_Components( $data );


// High performance order tables compatibility.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} 
);
