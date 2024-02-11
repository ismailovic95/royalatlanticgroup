<?php

namespace Octolize\Shipping\CanadaPost;

use OctolizeShippingCanadaPostVendor\WPDesk\PluginBuilder\Plugin\Hookable;

/**
 * Can display settings sidebar.
 */
class SettingsSidebar implements Hookable {

	/**
	 * Hooks.
	 */
	public function hooks() {
		add_action( 'octolize_canada_post_shipping_settings_sidebar', [ $this, 'display_settings_sidebar_when_no_pro_version' ] );
	}

	/**
	 * Maybe display settings sidebar.
	 *
	 * @return void
	 */
	public function display_settings_sidebar_when_no_pro_version() {
		if ( ! defined( 'OCTOLIZE_CANADA_POST_SHIPPING_PRO_VERSION' ) ) {
			$pro_url  = 'https://octol.io/cp-up-box';
			include __DIR__ . '/views/settings-sidebar-html.php';
		}
	}

}
