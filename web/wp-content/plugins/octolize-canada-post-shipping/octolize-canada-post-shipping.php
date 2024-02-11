<?php
/**
 * Plugin Name: Canada Post Live Rates
 * Plugin URI: https://wordpress.org/plugins/octolize-canada-post-shipping/
 * Description: Canada Post WooCommerce shipping methods with real-time calculated shipping rates based on the established Canada Post API connection.
 * Version: 1.7.15
 * Author: Octolize
 * Author URI: https://octol.io/cp-author
 * Text Domain: octolize-canada-post-shipping
 * Domain Path: /lang/
 * Requires at least: 5.8
 * Tested up to: 6.4
 * WC requires at least: 8.2
 * WC tested up to: 8.6
 * Requires PHP: 7.4
 *
 * Copyright 2019 WP Desk Ltd.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package Octolize\Shipping\CanadaPost
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THIS VARIABLE CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '1.7.15';

$plugin_name        = 'Octolize Canada Post Shipping';
$plugin_class_name  = '\Octolize\Shipping\CanadaPost\Plugin';
$plugin_text_domain = 'octolize-canada-post-shipping';
$product_id         = 'Octolize Shipping Canada Post';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;

define( $plugin_class_name, $plugin_version );
define( 'OCTOLIZE_CANADA_POST_SHIPPING_VERSION', $plugin_version );

$requirements = [
	'php'          => '7.4',
	'wp'           => '4.9',
	'repo_plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '5.0',
		],
	],
	'modules'      => [
		[
			'name'      => 'dom',
			'nice_name' => 'DOM',
		],
		[
			'name'      => 'intl',
			'nice_name' => 'Intl',
		],
	],
];

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';

require __DIR__ . '/vendor_prefixed/guzzlehttp/guzzle/src/functions_include.php';
require __DIR__ . '/vendor_prefixed/guzzlehttp/psr7/src/functions_include.php';
require __DIR__ . '/vendor_prefixed/guzzlehttp/promises/src/functions_include.php';
