<?php
/**
 * Main file of plugin
 *
 * @category PHP
 * @package  Currency_Switcher
 * @author   Display Name <ahemads@bsf.io>
 * @license  https://brainstormforce.com
 * @link     https://brainstormforce.com
 */

/**
 * Plugin Name:     Advanced Currency Switcher
 * Plugin URI:      https://www.brainstormforce.com/
 * Description:     This is automated currency converter, that displays product/service prices in different currencies. Visitors can easily switch currency rates in real-time so that you don't lose potential customers because of lack of ready currency conversions.
 * Version:         1.0.5
 * Author:          Pratik Chaskar
 * Author URI:      https://pratikchaskar.com/
 * Text Domain:     cswp
 * Domain Path:     /languages
 *
 * Main
 *
 * @category PHP
 * @package  Currency_Convertor_Addon
 * @author   Display Name <ahemads@bsf.io>
 * @license  https://brainstormforce.com
 * @link     https://brainstormforce.com
 */

require_once 'classes/class-cs-loader.php';

/**
 * Add a link to the settings page on the plugins.php page.
 *
 * @since 1.0.0
 *
 * @param  array $links List of existing plugin action links.
 * @return array         List of modified plugin action links.
 */
function cswp_plugin_action_links( $links ) {
	$links = array_merge(
		$links,
		array(
			'<a href="' . esc_url( admin_url( '/options-general.php?page=currency_switch' ) ) . '">' . __( 'Settings', 'advanced-currency-switcher' ) . '</a>',
		)
	);
	return $links;
}

add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cswp_plugin_action_links' );
