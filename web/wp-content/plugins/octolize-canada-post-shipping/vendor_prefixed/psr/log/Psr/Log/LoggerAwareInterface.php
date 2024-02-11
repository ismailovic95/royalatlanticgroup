<?php

namespace OctolizeShippingCanadaPostVendor\Psr\Log;

/**
 * Describes a logger-aware instance.
 */
interface LoggerAwareInterface
{
    /**
     * Sets a logger instance on the object.
     *
     * @param LoggerInterface $logger
     *
     * @return void
     */
    public function setLogger(\OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface $logger);
}
