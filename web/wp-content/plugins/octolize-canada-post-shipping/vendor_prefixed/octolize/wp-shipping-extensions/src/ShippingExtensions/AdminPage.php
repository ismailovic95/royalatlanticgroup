<?php

namespace OctolizeShippingCanadaPostVendor\Octolize\ShippingExtensions;

/**
 * .
 */
trait AdminPage
{
    /**
     * @return bool
     */
    public function is_shipping_extensions_page() : bool
    {
        return (\get_current_screen()->id ?? '') === \OctolizeShippingCanadaPostVendor\Octolize\ShippingExtensions\Page::SCREEN_ID;
    }
}
