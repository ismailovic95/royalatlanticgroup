<?php

/**
 * Shipping service abstract: ShippingService class.
 *
 * @package WPDesk\AbstractShipping
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping;

use OctolizeShippingCanadaPostVendor\Psr\Log\LoggerAwareInterface;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
/**
 * Basic abstract class for shipping classes.
 *
 * @package WPDesk\AbstractShipping
 */
abstract class ShippingService implements \OctolizeShippingCanadaPostVendor\Psr\Log\LoggerAwareInterface
{
    /**
     * Get unique ID.
     *
     * @return string
     */
    public abstract function get_unique_id();
    /**
     * Get name.
     *
     * @return string
     */
    public abstract function get_name();
    /**
     * Get description.
     *
     * @return string
     */
    public abstract function get_description();
    /**
     * Get settings definitions.
     *
     * @return SettingsDefinition
     */
    public abstract function get_settings_definition();
}