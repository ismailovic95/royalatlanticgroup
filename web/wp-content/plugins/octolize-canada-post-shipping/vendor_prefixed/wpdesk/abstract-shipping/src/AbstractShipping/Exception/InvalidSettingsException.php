<?php

/**
 * Custom Exception for InvalidSettingsException.
 *
 * @package WPDesk\AbstractShipping\Exception
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception;

/**
 * Exception thrown by service in case the settings do not pass the validation.
 *
 * @package WPDesk\AbstractShipping\Exception
 */
class InvalidSettingsException extends \RuntimeException implements \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\ShippingException
{
}
