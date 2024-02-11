<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\Persistence;

use OctolizeShippingCanadaPostVendor\Psr\Container\ContainerInterface;
/**
 * Container that allows to get all data stored by container.
 *
 * @package WPDesk\Persistence
 */
interface AllDataAccessContainer extends \OctolizeShippingCanadaPostVendor\Psr\Container\ContainerInterface
{
    /**
     * Get all values.
     *
     * @return array
     */
    public function get_all() : array;
}
