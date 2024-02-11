<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\Logger;

use OctolizeShippingCanadaPostVendor\Monolog\Logger;
/*
 * @package WPDesk\Logger
 */
interface LoggerFactory
{
    /**
     * Returns created Logger
     *
     * @param string $name
     *
     * @return Logger
     */
    public function getLogger($name);
}
