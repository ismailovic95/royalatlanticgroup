<?php

namespace OctolizeShippingCanadaPostVendor\Octolize\Tracker\OptInNotice;

/**
 * Should display AND conditions.
 */
class ShouldDisplayAndConditions implements \OctolizeShippingCanadaPostVendor\Octolize\Tracker\OptInNotice\ShouldDisplay
{
    /**
     * @var ShouldDisplay[]
     */
    private $conditions = [];
    /**
     * @param ShouldDisplay $should_display
     *
     * @return void
     */
    public function add_should_diaplay_condition(\OctolizeShippingCanadaPostVendor\Octolize\Tracker\OptInNotice\ShouldDisplay $should_display)
    {
        $this->conditions[] = $should_display;
    }
    /**
     * @inheritDoc
     */
    public function should_display()
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->should_display()) {
                return \false;
            }
        }
        return \true;
    }
}
