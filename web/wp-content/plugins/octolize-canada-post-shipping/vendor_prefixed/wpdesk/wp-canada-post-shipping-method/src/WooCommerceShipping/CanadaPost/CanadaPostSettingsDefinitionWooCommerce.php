<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost;

use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition;
/**
 * Can handle global and instance settings for WooCommerce shipping method.
 */
class CanadaPostSettingsDefinitionWooCommerce
{
    private $global_method_fields = [\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::SHIPPING_METHOD_TITLE, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::API_SETTINGS_TITLE, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::USERNAME, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::PASSWORD, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::CUSTOMER_NUMBER, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::ORIGIN_SETTINGS_TITLE, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::CUSTOM_ORIGIN, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::ORIGIN_ADDRESS, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::ORIGIN_CITY, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::ORIGIN_POSTCODE, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::ORIGIN_COUNTRY, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::ADVANCED_OPTIONS_TITLE, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::DEBUG_MODE, \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::API_STATUS];
    /**
     * Form fields.
     *
     * @var array
     */
    private $form_fields;
    /**
     * CanadaPostSettingsDefinitionWooCommerce constructor.
     *
     * @param array $form_fields Form fields.
     */
    public function __construct(array $form_fields)
    {
        $this->form_fields = $form_fields;
    }
    /**
     * Get form fields.
     *
     * @return array
     */
    public function get_form_fields()
    {
        return $this->filter_instance_fields($this->form_fields, \false);
    }
    /**
     * Get instance form fields.
     *
     * @return array
     */
    public function get_instance_form_fields()
    {
        return $this->filter_instance_fields($this->form_fields, \true);
    }
    /**
     * Get global method fields.
     *
     * @return array
     */
    protected function get_global_method_fields()
    {
        return $this->global_method_fields;
    }
    /**
     * Filter instance form fields.
     *
     * @param array $all_fields .
     * @param bool  $instance_fields .
     *
     * @return array
     */
    private function filter_instance_fields(array $all_fields, $instance_fields)
    {
        $fields = array();
        foreach ($all_fields as $key => $field) {
            $is_instance_field = !\in_array($key, $this->get_global_method_fields(), \true);
            if ($instance_fields && $is_instance_field || !$instance_fields && !$is_instance_field) {
                $fields[$key] = $field;
            }
        }
        return $fields;
    }
}
