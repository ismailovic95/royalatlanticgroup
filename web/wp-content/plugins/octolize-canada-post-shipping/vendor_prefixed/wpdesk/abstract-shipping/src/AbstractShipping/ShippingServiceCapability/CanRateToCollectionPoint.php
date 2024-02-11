<?php

/**
 * Capability: CanRateToCollectionPoint class
 *
 * @package WPDesk\AbstractShipping\ShippingServiceCapability
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability;

use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment;
/**
 * Interface for rate shipment to collection point
 */
interface CanRateToCollectionPoint
{
    /**
     * Rate shipment to collection point.
     *
     * @param SettingsValues  $settings Settings.
     * @param Shipment        $shipment Shipment.
     * @param CollectionPoint $collection_point Collection point.
     *
     * @return ShipmentRating
     */
    public function rate_shipment_to_collection_point(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\CollectionPoints\CollectionPoint $collection_point);
    /**
     * Is rate to collection point enabled?
     *
     * @param SettingsValues $settings
     *
     * @return mixed
     */
    public function is_rate_to_collection_point_enabled(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings);
}
