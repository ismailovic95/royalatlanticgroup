<?php

/**
 * Capability: CanRate class
 *
 * @package WPDesk\AbstractShipping\Shipment
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability;

use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment;
/**
 * Interface for rate shipment
 *
 * @package WPDesk\AbstractShipping\ShippingServiceCapability
 */
interface CanRate
{
    /**
     * Rate shipment.
     *
     * @param SettingsValues  $settings Settings.
     * @param Shipment        $shipment Shipment.
     *
     * @return ShipmentRating
     */
    public function rate_shipment(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment);
    /**
     * Is rate enabled?
     *
     * @param SettingsValues $settings .
     *
     * @return bool
     */
    public function is_rate_enabled(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings);
}
