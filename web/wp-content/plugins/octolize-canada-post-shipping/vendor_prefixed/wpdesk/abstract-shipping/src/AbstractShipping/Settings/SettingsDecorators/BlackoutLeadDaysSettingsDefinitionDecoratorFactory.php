<?php

/**
 * Class BlackoutLeadDaysSettingsDefinitionDecoratorFactory
 *
 * @package WPDesk\AbstractShipping\Settings\SettingsDecorators
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsDecorators;

/**
 * Can create Blackout Lead Days settings decorator.
 */
class BlackoutLeadDaysSettingsDefinitionDecoratorFactory extends \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsDecorators\AbstractDecoratorFactory
{
    const OPTION_ID = 'blackout_lead_days';
    /**
     * @return string
     */
    public function get_field_id()
    {
        return self::OPTION_ID;
    }
    /**
     * @return array
     */
    protected function get_field_settings()
    {
        return array('title' => \__('Blackout Lead Days', 'octolize-canada-post-shipping'), 'type' => 'multiselect', 'description' => \__('Blackout Lead Days are used to define days of the week when shop is not processing orders.', 'octolize-canada-post-shipping'), 'options' => array('1' => \__('Monday', 'octolize-canada-post-shipping'), '2' => \__('Tuesday', 'octolize-canada-post-shipping'), '3' => \__('Wednesday', 'octolize-canada-post-shipping'), '4' => \__('Thursday', 'octolize-canada-post-shipping'), '5' => \__('Friday', 'octolize-canada-post-shipping'), '6' => \__('Saturday', 'octolize-canada-post-shipping'), '7' => \__('Sunday', 'octolize-canada-post-shipping')), 'custom_attributes' => array('size' => 7), 'class' => 'wc-enhanced-select', 'desc_tip' => \true, 'default' => '');
    }
}
