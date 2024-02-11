<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService;

use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShippingFields;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Fallback\FallbackRateMethod;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\WooCommerceNotInitializedException;
/**
 * A class that defines the basic settings for the shipping method.
 */
class CanadaPostSettingsDefinition extends \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsDefinition
{
    const CUSTOM_SERVICES_CHECKBOX_CLASS = 'wpdesk_wc_shipping_custom_service_checkbox';
    const SHIPPING_METHOD_TITLE = 'shipping_method_title';
    const API_SETTINGS_TITLE = 'api_settings_title';
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const CUSTOMER_NUMBER = 'customer_number';
    const TESTING = 'testing';
    const ORIGIN_SETTINGS_TITLE = 'origin_settings_title';
    const CUSTOM_ORIGIN = 'custom_origin';
    const ORIGIN_ADDRESS = 'origin_address';
    const ORIGIN_CITY = 'origin_city';
    const ORIGIN_POSTCODE = 'origin_postcode';
    const ORIGIN_COUNTRY = 'origin_country';
    const ADVANCED_OPTIONS_TITLE = 'advanced_options_title';
    const DEBUG_MODE = 'debug_mode';
    const API_STATUS = 'api_status';
    const METHOD_SETTINGS_TITLE = 'method_settings_title';
    const TITLE = 'title';
    const FALLBACK = 'fallback';
    const CUSTOM_SERVICES = 'custom_services';
    const SERVICES = 'services';
    const PACKAGE_SETTINGS_TITLE = 'package_settings_title';
    const PACKAGE_LENGTH = 'package_length';
    const PACKAGE_WIDTH = 'package_width';
    const PACKAGE_HEIGHT = 'package_height';
    const PACKAGE_WEIGHT = 'package_weight';
    const RATE_ADJUSTMENTS_TITLE = 'rate_adjustments_title';
    const INSURANCE = 'insurance';
    const REMOVE_TAX = 'remove_tax';
    const FREE_SHIPPING = 'free_shipping';
    const QUOTE_TYPE = 'quote_type';
    /**
     * Shop settings.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /**
     * CanadaPostSettingsDefinition constructor.
     *
     * @param ShopSettings $shop_settings Shop settings.
     */
    public function __construct(\OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings $shop_settings)
    {
        $this->shop_settings = $shop_settings;
    }
    /**
     * Validate settings.
     *
     * @param SettingsValues $settings Settings.
     *
     * @return bool
     */
    public function validate_settings(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings) : bool
    {
        return \true;
    }
    /**
     * Prepare country state options.
     *
     * @return array
     */
    private function prepare_country_state_options() : array
    {
        try {
            $countries = $this->shop_settings->get_countries();
        } catch (\OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\WooCommerceNotInitializedException $e) {
            $countries = array();
        }
        $country_state_options = $countries;
        foreach ($country_state_options as $country_code => $country) {
            $states = $this->shop_settings->get_states($country_code);
            if ($states) {
                unset($country_state_options[$country_code]);
                foreach ($states as $state_code => $state_name) {
                    $country_state_options[$country_code . ':' . $state_code] = $country . ' &mdash; ' . $state_name;
                }
            }
        }
        return $country_state_options;
    }
    /**
     * Initialise Settings Form Fields.
     *
     * @return array
     */
    public function get_form_fields()
    {
        $services = new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostServices();
        $docs_link = 'https://octol.io/cp-method-docs';
        $connection_fields = array(self::SHIPPING_METHOD_TITLE => array('title' => \__('Canada Post', 'octolize-canada-post-shipping'), 'type' => 'title', 'description' => \sprintf(
            // Translators: docs link.
            \__('These are the Canada Post Live Rates plugin general settings. In order to learn more about its configuration please refer to its %1$sdedicated documentation →%2$s', 'octolize-canada-post-shipping'),
            '<a href="' . $docs_link . '" target="_blank">',
            '</a>'
        )), self::API_SETTINGS_TITLE => array(
            'title' => \__('API Settings', 'octolize-canada-post-shipping'),
            'type' => 'title',
            // Translators: link.
            'description' => \sprintf(\__('Enter your Canada Post API credentials. Please mind that they are different than your standard CP account login details. What\'s more, your business account needs to take part in the Developer Program, which is required to obtain the live rates via API connection. If you do not have the Canada Post business account or it\'s not in the Developer Program yet, please follow the instructions from our guide on %1$show to create a Canada Post account →%2$s', 'octolize-canada-post-shipping'), '<a href="https://octol.io/cp-account" target="_blank">', '</a>'),
        ), self::USERNAME => array('title' => \__('API Key Username *', 'octolize-canada-post-shipping'), 'type' => 'text', 'custom_attributes' => array('required' => 'required'), 'description' => \__('Enter the credentials you acquired during the Canada Post account registration process.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'default' => ''), self::PASSWORD => array('title' => \__('API Key Password *', 'octolize-canada-post-shipping'), 'type' => 'password', 'custom_attributes' => array('required' => 'required'), 'description' => \__('Enter the credentials you acquired during the Canada Post account registration process.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'default' => ''), self::CUSTOMER_NUMBER => array('title' => \__('Customer Number *', 'octolize-canada-post-shipping'), 'type' => 'text', 'custom_attributes' => array('required' => 'required'), 'description' => \__('Enter the credentials you acquired during the Canada Post account registration process.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'default' => ''));
        if ($this->shop_settings->is_testing()) {
            $connection_fields[self::TESTING] = ['title' => \__('Test Credentials', 'fedex-shipping-service'), 'type' => 'checkbox', 'label' => \__('Enable to use test credentials', 'fedex-shipping-service'), 'desc_tip' => \true, 'default' => 'no'];
        }
        $fields = array(self::ADVANCED_OPTIONS_TITLE => array('title' => \__('Advanced Options', 'octolize-canada-post-shipping'), 'type' => 'title'), self::DEBUG_MODE => array('title' => \__('Debug Mode', 'octolize-canada-post-shipping'), 'label' => \__('Enable debug mode', 'octolize-canada-post-shipping'), 'type' => 'checkbox', 'description' => \__('Enable debug mode to display additional tech information, incl. the data sent to Canada Post API, visible only for Admins and Shop Managers in the cart and checkout.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'default' => 'no'));
        $instance_fields = array(self::METHOD_SETTINGS_TITLE => array('title' => \__('Method Settings', 'octolize-canada-post-shipping'), 'description' => \__('Manage the way how the Canada Post services are displayed in the cart and checkout.', 'octolize-canada-post-shipping'), 'type' => 'title'), self::TITLE => array('title' => \__('Method Title', 'octolize-canada-post-shipping'), 'type' => 'text', 'description' => \__('Define the Canada Post shipping method title which should be used in the cart/checkout when the Fallback option was triggered.', 'octolize-canada-post-shipping'), 'default' => \__('Canada Post Live Rates', 'octolize-canada-post-shipping'), 'desc_tip' => \true), self::FALLBACK => array('type' => \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShippingMethod\RateMethod\Fallback\FallbackRateMethod::FIELD_TYPE_FALLBACK, 'description' => \__('Enable to offer flat rate cost for shipping so that the user can still checkout, if API for some reason returns no matching rates.', 'octolize-canada-post-shipping'), 'default' => ''), self::FREE_SHIPPING => array('title' => \__('Free Shipping', 'octolize-canada-post-shipping'), 'type' => \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShippingFields::FIELD_TYPE_FREE_SHIPPING, 'default' => ''), self::CUSTOM_SERVICES => array('title' => \__('Services', 'octolize-canada-post-shipping'), 'label' => \__('Enable the services\' custom settings', 'octolize-canada-post-shipping'), 'type' => 'checkbox', 'description' => \__('Decide which services should be displayed and which not, change their names and order. Please mind that enabling a service does not guarantee it will be visible in the cart/checkout. It has to be available for the provided package weight, origin and destination in order to be displayed.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'class' => self::CUSTOM_SERVICES_CHECKBOX_CLASS, 'default' => 'no'), self::SERVICES => array('title' => \__('Services Table', 'octolize-canada-post-shipping'), 'type' => 'services', 'default' => '', 'options' => $services->get_all_services()), self::PACKAGE_SETTINGS_TITLE => array('title' => \__('Package Settings', 'octolize-canada-post-shipping'), 'description' => \sprintf(\__('Define the package details including its dimensions and weight which will be used as default for this shipping method.', 'octolize-canada-post-shipping')), 'type' => 'title'), self::PACKAGE_LENGTH => array('title' => \__('Length [cm] *', 'octolize-canada-post-shipping'), 'type' => 'number', 'description' => \__('Enter only a numeric value without the metric symbol.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'custom_attributes' => array('min' => 0.1, 'step' => 0.1)), self::PACKAGE_WIDTH => array('title' => \__('Width [cm] *', 'octolize-canada-post-shipping'), 'type' => 'number', 'description' => \__('Enter only a numeric value without the metric symbol.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'custom_attributes' => array('min' => 0.1, 'step' => 0.1)), self::PACKAGE_HEIGHT => array('title' => \__('Height [cm] *', 'octolize-canada-post-shipping'), 'type' => 'number', 'description' => \__('Enter only a numeric value without the metric symbol.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'custom_attributes' => array('min' => 0.1, 'step' => 0.1)), self::PACKAGE_WEIGHT => array('title' => \__('Default weight [kg] *', 'octolize-canada-post-shipping'), 'type' => 'number', 'description' => \__('Enter the package weight value which will be used as default if none of the products\' in the cart individual weight has been filled in or if the cart total weight equals 0 kg.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'custom_attributes' => array('min' => 0.001, 'step' => 0.001)), self::RATE_ADJUSTMENTS_TITLE => array('title' => \__('Rates Adjustments', 'octolize-canada-post-shipping'), 'description' => \sprintf(\__('Use these settings and adjust them to your needs to get more accurate rates. Read %1$swhat affects the Canada Post rates in Canada Post WooCommerce plugin →%2$s', 'octolize-canada-post-shipping'), \sprintf('<a href="%s" target="_blank">', \__('https://octol.io/cp-free-rates', 'octolize-canada-post-shipping')), '</a>'), 'type' => 'title'), self::QUOTE_TYPE => array('title' => \__('Quote Type', 'octolize-canada-post-shipping'), 'type' => 'select', 'description' => \__('Select \'Commercial\' if you are a commercial customer or a \'Solutions for Small Business\' program member and want to offer your customers the discounted rates. Choose \'Counter\' if you want the regular rates to be displayed instead.', 'octolize-canada-post-shipping'), 'options' => ['commercial' => \__('Commercial', 'octolize-canada-post-shipping'), 'counter' => \__('Counter', 'octolize-canada-post-shipping')], 'desc_tip' => \true, 'default' => 'commercial'), self::INSURANCE => array('title' => \__('Insurance', 'octolize-canada-post-shipping'), 'label' => \__('Request insurance to be included in the Canada Post rates', 'octolize-canada-post-shipping'), 'type' => 'checkbox', 'description' => \__('Enable if you want to include insurance in the Canada Post rates if possible.', 'octolize-canada-post-shipping'), 'desc_tip' => \true, 'default' => 'no'), self::REMOVE_TAX => array('title' => \__('Tax', 'octolize-canada-post-shipping'), 'label' => \__('Remove the GST, HST and PST', 'octolize-canada-post-shipping'), 'type' => 'checkbox', 'description' => \__('Tick this checkbox in order to strip the GST, HST and PST tax value from the shipping rates coming from Canada Post.', 'octolize-canada-post-shipping'), 'desc_tip' => \false, 'default' => 'no'));
        return $connection_fields + $fields + $instance_fields;
    }
}
