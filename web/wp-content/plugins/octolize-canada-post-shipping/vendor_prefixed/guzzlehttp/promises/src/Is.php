<?php

namespace OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise;

final class Is
{
    /**
     * Returns true if a promise is pending.
     *
     * @return bool
     */
    public static function pending(\OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled or rejected.
     *
     * @return bool
     */
    public static function settled(\OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() !== \OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise\PromiseInterface::PENDING;
    }
    /**
     * Returns true if a promise is fulfilled.
     *
     * @return bool
     */
    public static function fulfilled(\OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise\PromiseInterface::FULFILLED;
    }
    /**
     * Returns true if a promise is rejected.
     *
     * @return bool
     */
    public static function rejected(\OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise\PromiseInterface $promise)
    {
        return $promise->getState() === \OctolizeShippingCanadaPostVendor\GuzzleHttp\Promise\PromiseInterface::REJECTED;
    }
}
