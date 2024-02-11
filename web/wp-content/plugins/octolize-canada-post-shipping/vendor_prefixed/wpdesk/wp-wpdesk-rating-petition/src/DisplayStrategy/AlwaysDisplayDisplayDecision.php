<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\RepositoryRating\DisplayStrategy;

/**
 * DisplayDecision - always display.
 */
class AlwaysDisplayDisplayDecision implements \OctolizeShippingCanadaPostVendor\WPDesk\RepositoryRating\DisplayStrategy\DisplayDecision
{
    /**
     * Should display?
     *
     * @return bool
     */
    public function should_display() : bool
    {
        return \true;
    }
}
