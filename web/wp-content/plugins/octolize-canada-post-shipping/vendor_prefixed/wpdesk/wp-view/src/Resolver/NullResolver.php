<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\View\Resolver;

use OctolizeShippingCanadaPostVendor\WPDesk\View\Renderer\Renderer;
use OctolizeShippingCanadaPostVendor\WPDesk\View\Resolver\Exception\CanNotResolve;
/**
 * This resolver never finds the file
 *
 * @package WPDesk\View\Resolver
 */
class NullResolver implements \OctolizeShippingCanadaPostVendor\WPDesk\View\Resolver\Resolver
{
    public function resolve($name, \OctolizeShippingCanadaPostVendor\WPDesk\View\Renderer\Renderer $renderer = null)
    {
        throw new \OctolizeShippingCanadaPostVendor\WPDesk\View\Resolver\Exception\CanNotResolve("Null Cannot resolve");
    }
}
