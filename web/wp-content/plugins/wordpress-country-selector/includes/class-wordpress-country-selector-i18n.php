<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://plugins.db-dzine.com
 * @since      1.0.0
 *
 * @package    Wordpress_Country_Selector
 * @subpackage Wordpress_Country_Selector/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wordpress_Country_Selector
 * @subpackage Wordpress_Country_Selector/includes
 * @author     Daniel Barenkamp <contact@db-dzine.com>
 */
class Wordpress_Country_Selector_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$loaded = load_plugin_textdomain(
			'wordpress-country-selector',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
