<?php

/**
 * Ajax status handler.
 *
 * @package WPDesk\WooCommerceShipping\CanadaPost
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost;

use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\ConnectionChecker;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax;
/**
 * Can handle api status ajax request.
 */
class CanadaPostFieldApiStatusAjax extends \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax
{
    /**
     * Check connection error.
     *
     * @return string|false
     */
    protected function check_connection_error()
    {
        try {
            $this->ping();
            return \false;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * Ping api.
     *
     * @throws \Exception
     */
    private function ping()
    {
        $connection_checker = new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\ConnectionChecker($this->get_settings(), $this->get_logger());
        $connection_checker->check_connection();
    }
}
