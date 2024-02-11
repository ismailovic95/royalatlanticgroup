<?php

namespace OctolizeShippingCanadaPostVendor\GuzzleHttp\Psr7;

use OctolizeShippingCanadaPostVendor\Psr\Http\Message\UriInterface;
/**
 * Provides methods to determine if a modified URL should be considered cross-origin.
 *
 * @author Graham Campbell
 */
final class UriComparator
{
    /**
     * Determines if a modified URL should be considered cross-origin with
     * respect to an original URL.
     *
     * @return bool
     */
    public static function isCrossOrigin(\OctolizeShippingCanadaPostVendor\Psr\Http\Message\UriInterface $original, \OctolizeShippingCanadaPostVendor\Psr\Http\Message\UriInterface $modified)
    {
        if (\strcasecmp($original->getHost(), $modified->getHost()) !== 0) {
            return \true;
        }
        if ($original->getScheme() !== $modified->getScheme()) {
            return \true;
        }
        if (self::computePort($original) !== self::computePort($modified)) {
            return \true;
        }
        return \false;
    }
    /**
     * @return int
     */
    private static function computePort(\OctolizeShippingCanadaPostVendor\Psr\Http\Message\UriInterface $uri)
    {
        $port = $uri->getPort();
        if (null !== $port) {
            return $port;
        }
        return 'https' === $uri->getScheme() ? 443 : 80;
    }
    private function __construct()
    {
        // cannot be instantiated
    }
}