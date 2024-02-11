<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService;

use OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\RateException;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\ShipmentRating;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\ShipmentRatingImplementation;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\SingleRate;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingService;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability\HasSettings;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\ConnectionChecker;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\CanadaPostRateReplyInterpretation;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\CanadaPostRateRequestBuilder;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Exception\CurrencySwitcherException;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings;
/**
 * Canada Post main shipping class injected into WooCommerce shipping method.
 */
class CanadaPostShippingService extends \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingService implements \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability\HasSettings, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanRate, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\ShippingServiceCapability\CanTestSettings
{
    /** Logger.
     *
     * @var LoggerInterface
     */
    private $logger;
    /** Shipping method helper.
     *
     * @var ShopSettings
     */
    private $shop_settings;
    /**
     * Origin country.
     *
     * @var string
     */
    private $origin_country;
    const UNIQUE_ID = 'octolize_canada_post_shipping';
    /**
     * CanadaPostShippingService constructor.
     *
     * @param LoggerInterface $logger Logger.
     * @param ShopSettings    $shop_settings Helper.
     * @param string          $origin_country Origin country.
     */
    public function __construct(\OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface $logger, \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings $shop_settings, string $origin_country)
    {
        $this->logger = $logger;
        $this->shop_settings = $shop_settings;
        $this->origin_country = $origin_country;
    }
    /**
     * Set logger.
     *
     * @param LoggerInterface $logger Logger.
     */
    public function setLogger(\OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * .
     *
     * @return LoggerInterface
     */
    public function get_logger() : \OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface
    {
        return $this->logger;
    }
    /**
     * .
     *
     * @return ShopSettings
     */
    public function get_shop_settings() : \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings
    {
        return $this->shop_settings;
    }
    /**
     * Is standard rate enabled?
     *
     * @param SettingsValues $settings .
     *
     * @return bool
     */
    public function is_rate_enabled(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings) : bool
    {
        return \true;
    }
    /**
     * Rate shipment.
     *
     * @param SettingsValues $settings Settings.
     * @param Shipment       $shipment Shipment.
     *
     * @return ShipmentRating
     * @throws InvalidSettingsException InvalidSettingsException.
     * @throws RateException RateException.
     * @throws UnitConversionException Weight exception.
     */
    public function rate_shipment(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment) : \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\ShipmentRating
    {
        if (!$this->get_settings_definition()->validate_settings($settings)) {
            throw new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\InvalidSettingsException();
        }
        $this->verify_currency($this->shop_settings->get_default_currency(), $this->shop_settings->get_currency());
        $rates = $this->get_rates($settings, $shipment);
        return new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\ShipmentRatingImplementation($rates);
    }
    private function get_rates(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment)
    {
        $rates = [];
        $first_package = \true;
        foreach ($shipment->packages as $package) {
            $single_package_shipment = new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment();
            $single_package_shipment->packages = [$package];
            $single_package_shipment->ship_from = $shipment->ship_from;
            $single_package_shipment->ship_to = $shipment->ship_to;
            $single_package_shipment->insurance = $shipment->insurance;
            $single_package_shipment->packed = $shipment->packed;
            $request_builder = $this->create_rate_request_builder($settings, $single_package_shipment, $this->shop_settings);
            $request_builder->build_request();
            $request = $request_builder->get_build_request();
            $response = $request->getRate();
            $reply = $this->create_reply_interpretation($response, $this->shop_settings, $settings);
            if ($reply->has_reply_warning()) {
                $this->logger->info($reply->get_reply_message());
            }
            if (!$reply->has_reply_error()) {
                $single_rates = $this->filter_service_rates($settings, $reply->get_rates());
                if ($first_package) {
                    $first_package = \false;
                    $rates = $single_rates;
                } else {
                    $rates = $this->merge_rates($rates, $single_rates);
                }
            }
        }
        return $rates;
    }
    /**
     * @param SingleRate[] $rates
     * @param SingleRate[] $single_rates
     *
     * @return SingleRate[]
     */
    private function merge_rates(array $rates, array $single_rates) : array
    {
        foreach ($rates as $key => $rate) {
            $single_rate = $this->find_rate_by_service($rate->service_name, $rate->service_type, $single_rates);
            if ($single_rate) {
                $rate->total_charge->amount += $single_rate->total_charge->amount;
            } else {
                unset($rates[$key]);
            }
        }
        return $rates;
    }
    /**
     * @param string $service_name
     * @param string $service_type
     * @param SingleRate[] $rates
     *
     * @return SingleRate|null
     */
    private function find_rate_by_service(string $serice_name, string $service_type, array $rates)
    {
        foreach ($rates as $rate) {
            if ($rate->service_name === $serice_name && $rate->service_type === $service_type) {
                return $rate;
            }
        }
        return null;
    }
    /**
     * @param string $xml_string
     *
     * @return string
     */
    private function pretty_print_xml($xml_string) : string
    {
        $xml = new \DOMDocument();
        $xml->preserveWhiteSpace = \false;
        $xml->formatOutput = \true;
        $xml->loadXML($xml_string);
        return $xml->saveXML();
    }
    /**
     * Create reply interpretation.
     *
     * @param \DOMDocument   $response .
     * @param ShopSettings   $shop_settings .
     * @param SettingsValues $settings .
     *
     * @return CanadaPostRateReplyInterpretation
     */
    protected function create_reply_interpretation($response, $shop_settings, $settings) : \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\CanadaPostRateReplyInterpretation
    {
        return new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\CanadaPostRateReplyInterpretation($response, $settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::REMOVE_TAX, 'no') === 'yes');
    }
    /**
     * Create rate request builder.
     *
     * @param SettingsValues $settings .
     * @param Shipment       $shipment .
     * @param ShopSettings   $shop_settings .
     *
     * @return CanadaPostRateRequestBuilder
     */
    protected function create_rate_request_builder(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings $shop_settings) : \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\CanadaPostRateRequestBuilder
    {
        return new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\CanadaPostRateRequestBuilder($settings, $shipment, $shop_settings, $this->logger);
    }
    /**
     * Verify currency.
     *
     * @param string $default_shop_currency .
     * @param string $currency .
     *
     * @throws CurrencySwitcherException .
     */
    protected function verify_currency(string $default_shop_currency, string $currency)
    {
        if ('CAD' !== $currency || 'CAD' !== $default_shop_currency) {
            throw new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Exception\CurrencySwitcherException();
        }
    }
    /**
     * Get settings
     *
     * @return CanadaPostSettingsDefinition
     */
    public function get_settings_definition() : \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition
    {
        return new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition($this->shop_settings);
    }
    /**
     * Get unique ID.
     *
     * @return string
     */
    public function get_unique_id() : string
    {
        return self::UNIQUE_ID;
    }
    /**
     * Get name.
     *
     * @return string
     */
    public function get_name() : string
    {
        return \__('Canada Post Live Rates', 'octolize-canada-post-shipping');
    }
    /**
     * Get description.
     *
     * @return string
     */
    public function get_description() : string
    {
        return \__('Canada Post integration', 'octolize-canada-post-shipping');
    }
    /**
     * Pings API.
     * Returns empty string on success or error message on failure.
     *
     * @param SettingsValues  $settings .
     * @param LoggerInterface $logger .
     *
     * @return string
     */
    public function check_connection(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface $logger) : string
    {
        try {
            $connection_checker = new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\ConnectionChecker($settings, $logger);
            $connection_checker->check_connection();
            return '';
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /**
     * Returns field ID after which API Status field should be added.
     *
     * @return string
     */
    public function get_field_before_api_status_field() : string
    {
        return \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::DEBUG_MODE;
    }
    /**
     * Filter&change rates according to settings.
     *
     * @param SettingsValues $settings Settings.
     * @param SingleRate[]   $canada_post_rates Response.
     *
     * @return SingleRate[]
     */
    private function filter_service_rates(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, array $canada_post_rates) : array
    {
        $rates = [];
        if (!empty($canada_post_rates)) {
            $all_services = $this->get_services();
            $services_settings = $this->get_services_settings($settings);
            if ($this->is_custom_services_enable($settings)) {
                foreach ($canada_post_rates as $service) {
                    if (isset($service->service_type, $services_settings[$service->service_type]) && !empty($services_settings[$service->service_type]['enabled'])) {
                        $service->service_name = $services_settings[$service->service_type]['name'];
                        $rates[$service->service_type] = $service;
                    }
                }
                $rates = $this->sort_services($rates, $services_settings);
            } else {
                foreach ($canada_post_rates as $service) {
                    if (isset($service->service_type, $all_services[$service->service_type])) {
                        $service->service_name = $all_services[$service->service_type];
                        $rates[$service->service_type] = $service;
                    }
                }
            }
        }
        return $rates;
    }
    /**
     * @return array
     */
    private function get_services() : array
    {
        $canada_post_services = new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostServices();
        return $canada_post_services->get_all_services();
    }
    /**
     * @param SettingsValues $settings Settings.
     * @param bool           $is_domestic Domestic rates.
     *
     * @return array
     */
    private function get_services_settings(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings) : array
    {
        $services_settings = $settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::SERVICES, []);
        return \is_array($services_settings) ? $services_settings : [];
    }
    /**
     * Sort rates according to order set in admin settings.
     *
     * @param SingleRate[] $rates           Rates.
     * @param array        $option_services Saved services to settings.
     *
     * @return SingleRate[]
     */
    private function sort_services(array $rates, array $option_services) : array
    {
        if (!empty($option_services)) {
            $services = [];
            foreach ($option_services as $service_code => $service_name) {
                if (isset($rates[$service_code])) {
                    $services[] = $rates[$service_code];
                }
            }
            return $services;
        }
        return $rates;
    }
    /**
     * Are customs service settings enabled.
     *
     * @param SettingsValues $settings Values.
     *
     * @return bool
     */
    private function is_custom_services_enable(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings) : bool
    {
        return $settings->has_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::CUSTOM_SERVICES) && 'yes' === $settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::CUSTOM_SERVICES);
    }
}
