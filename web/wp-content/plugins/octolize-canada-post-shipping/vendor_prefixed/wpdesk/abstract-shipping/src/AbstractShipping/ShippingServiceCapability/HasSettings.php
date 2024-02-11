<?php

/**
 * Capability: HasSettings class.
 *
 * @package WPDesk\AbstractShipping\ShippingServiceCapability
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability;

use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
/**
 * Interface for get settings definition
 *
 * @package WPDesk\AbstractShipping\ShippingServiceCapability
 */
interface HasSettings
{
    /**
     * Get settings definition.
     *
     * @return SettingsDefinition
     */
    public function get_settings_definition();
}
