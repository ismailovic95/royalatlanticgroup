<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost;

use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostServices;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingBuilder\AddressProvider;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingBuilder\CustomOriginAddressSender;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingBuilder\WooCommerceAddressSender;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingMethod;
/**
 * Canada Post Shipping Method.
 */
class CanadaPostShippingMethod extends \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingMethod implements \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasFreeShipping, \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingMethod\HasRateCache
{
    /**
     * Supports.
     *
     * @var array
     */
    public $supports = array('settings', 'shipping-zones', 'instance-settings');
    /**
     * @var FieldApiStatusAjax
     */
    protected static $api_status_ajax_handler;
    /**
     * Set api status field AJAX handler.
     *
     * @param FieldApiStatusAjax $api_status_ajax_handler .
     */
    public static function set_api_status_ajax_handler(\OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CustomFields\ApiStatus\FieldApiStatusAjax $api_status_ajax_handler)
    {
        static::$api_status_ajax_handler = $api_status_ajax_handler;
    }
    /**
     * Prepare description.
     * Description depends on current page.
     *
     * @return string
     */
    private function prepare_description()
    {
        $docs_link = 'https://octol.io/cp-zone-docs';
        return \sprintf(
            // Translators: docs URL.
            \__('Dynamically calculated Canada Post Live rates based on established Canada Post API connection. %1$sLearn more →%2$s', 'octolize-canada-post-shipping'),
            '<a target="_blank" href="' . $docs_link . '">',
            '</a>'
        );
    }
    /**
     * Init method.
     */
    public function init()
    {
        parent::init();
        $this->method_description = $this->prepare_description();
    }
    /**
     * Init form fields.
     */
    public function build_form_fields()
    {
        $canada_post_settings_definition = new \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost\CanadaPostSettingsDefinitionWooCommerce($this->form_fields);
        $this->form_fields = $canada_post_settings_definition->get_form_fields();
        $this->instance_form_fields = $canada_post_settings_definition->get_instance_form_fields();
    }
    /**
     * Create meta data builder.
     *
     * @return CanadaPostMetaDataBuilder
     */
    protected function create_metadata_builder()
    {
        return new \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\CanadaPost\CanadaPostMetaDataBuilder($this);
    }
    /**
     * Render shipping method settings.
     */
    public function admin_options()
    {
        if ($this->instance_id) {
            $canada_post_services = new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostServices();
            $shipping_zone = $this->get_zone_for_shipping_method($this->instance_id);
            $services_options = [];
            if ($this->is_zone_for_domestic_ca_services($shipping_zone)) {
                $services_options = \array_merge($services_options, $canada_post_services->get_services_domestic_ca());
            }
            if ($this->is_zone_for_us_services($shipping_zone)) {
                $services_options = \array_merge($services_options, $canada_post_services->get_services_us());
            }
            if ($this->is_zone_for_international_services($shipping_zone)) {
                $services_options = \array_merge($services_options, $canada_post_services->get_services_international());
            }
            $this->instance_form_fields[\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::SERVICES]['options'] = $services_options;
        }
        parent::admin_options();
        include __DIR__ . '/view/shipping-method-script.php';
    }
    /**
     * Is custom origin?
     *
     * @return bool
     */
    public function is_custom_origin()
    {
        return 'yes' === $this->get_option(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::CUSTOM_ORIGIN, 'no');
    }
    /**
     * @param int $instance_id
     *
     * @return \WC_Shipping_Zone
     */
    private function get_zone_for_shipping_method($instance_id)
    {
        $woocommerce_shipping_zones = \WC_Shipping_Zones::get_zones();
        $zone = new \WC_Shipping_Zone();
        foreach ($woocommerce_shipping_zones as $woocommerce_shipping_zone) {
            foreach ($woocommerce_shipping_zone['shipping_methods'] as $woocommerce_shipping_method) {
                if ($woocommerce_shipping_method->instance_id === $instance_id) {
                    $zone = \WC_Shipping_Zones::get_zone($woocommerce_shipping_zone['id']);
                }
            }
        }
        return $zone;
    }
    /**
     * @param \WC_Shipping_Zone $zone
     *
     * @return bool
     */
    private function is_zone_for_domestic_ca_services(\WC_Shipping_Zone $zone)
    {
        $is_domestic = \false;
        $zone_locations = $zone->get_zone_locations();
        if (\count($zone_locations)) {
            foreach ($zone_locations as $zone_location) {
                if ('country' === $zone_location->type || 'state' === $zone_location->type) {
                    $code_exploded = \explode(':', $zone_location->code);
                    $country_code = $code_exploded[0];
                    $is_domestic = $is_domestic || 'CA' === $country_code;
                }
            }
        } else {
            $is_domestic = \true;
        }
        return $is_domestic;
    }
    /**
     * @param \WC_Shipping_Zone $zone
     *
     * @return bool
     */
    private function is_zone_for_international_services(\WC_Shipping_Zone $zone)
    {
        $is_international = \false;
        $zone_locations = $zone->get_zone_locations();
        if (\count($zone_locations)) {
            foreach ($zone_locations as $zone_location) {
                if ('country' === $zone_location->type || 'state' === $zone_location->type) {
                    $code_exploded = \explode(':', $zone_location->code);
                    $country_code = $code_exploded[0];
                    $is_international = $is_international || 'CA' !== $country_code && 'US' !== $country_code;
                } elseif ('continent' === $zone_location->type) {
                    $is_international = \true;
                }
            }
        } else {
            $is_international = \true;
        }
        return $is_international;
    }
    /**
     * @param \WC_Shipping_Zone $zone
     *
     * @return bool
     */
    private function is_zone_for_us_services(\WC_Shipping_Zone $zone)
    {
        $is_us = \false;
        $zone_locations = $zone->get_zone_locations();
        if (\count($zone_locations)) {
            foreach ($zone_locations as $zone_location) {
                if ('country' === $zone_location->type || 'state' === $zone_location->type) {
                    $code_exploded = \explode(':', $zone_location->code);
                    $country_code = $code_exploded[0];
                    $is_us = $is_us || 'US' === $country_code;
                }
            }
        } else {
            $is_us = \true;
        }
        return $is_us;
    }
}
