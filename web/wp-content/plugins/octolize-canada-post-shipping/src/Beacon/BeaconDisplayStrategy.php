<?php

namespace Octolize\Shipping\CanadaPost\Beacon;

use OctolizeShippingCanadaPostVendor\WPDesk\Beacon\BeaconGetShouldShowStrategy;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost\CanadaPostShippingMethod;

/**
 * Beacon display strategy.
 */
class BeaconDisplayStrategy extends BeaconGetShouldShowStrategy {

	/**
	 * BeaconDisplayStrategy constructor.
	 */
	public function __construct() {
		$conditions = [
			[
				'page' => 'wc-settings',
				'tab'  => 'shipping',
			],
		];
		parent::__construct( $conditions );
	}

	/**
	 * Should Beacon be visible?
	 *
	 * @return bool
	 */
	public function shouldDisplay() {
		if ( parent::shouldDisplay() ) {
			if ( isset( $_GET['instance_id'] ) ) { // phpcs:ignore
				$instance_id = sanitize_text_field( wp_unslash( $_GET['instance_id'] ) );
				try {
					$shipping_method = \WC_Shipping_Zones::get_shipping_method( (int) $instance_id );
					if ( $shipping_method && ( ( $shipping_method instanceof CanadaPostShippingMethod ) ) ) {

						return true;
					}
				} catch ( \Exception $e ) {

					return false;
				}
			}
			if ( isset( $_GET['section'] ) && sanitize_key( $_GET['section'] ) === 'octolize_canada_post_shipping' ) { // phpcs:ignore /** @phpstan-ignore-line */

				return true;
			}
		}

		return false;
	}
}
