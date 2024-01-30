<?php
/**
 * Add submenu of Global settings Page to admin menu.
 *
 * @category PHP
 * @package  Currency_Switcher
 * @author   Display Name <ahemads@bsf.io>
 * @license  https://brainstormforce.com
 * @link     https://brainstormforce.com
 */

/**
 * Custom modules
 */
if ( ! class_exists( 'CS_Menu_Page' ) ) {

	/**
	 * Class define for Menu.
	 *
	 * @class    CS_Menu_Page
	 * @category PHP
	 * @package  Currency_Switcher
	 * @author   Display Name <ahemads@bsf.io>
	 * @license  https://brainstormforce.com
	 * @link     https://brainstormforce.com
	 */
	class CS_Menu_Page {


		/**
		 * Constructor
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'cswp_options_page' ) );

		}

		/**
		 * Define cs_options_page.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function cswp_options_page() {
			add_submenu_page(
				'options-general.php',
				'Currency Switcher',
				'Currency Switcher',
				'manage_options',
				'currency_switch',
				array( $this, 'cswp_advance_currency_page' )
			);
		}

		/**
		 * Main Frontpage.
		 *
		 * @since  1.0.0
		 * @return void
		 */
		public function cswp_advance_currency_page() {

			// require main-frontend tab file.
			require_once CSWP_PLUGIN_DIR . '/includes/cs-main-frontend.php';
		}

	}
	$menup = new CS_Menu_Page();
}
