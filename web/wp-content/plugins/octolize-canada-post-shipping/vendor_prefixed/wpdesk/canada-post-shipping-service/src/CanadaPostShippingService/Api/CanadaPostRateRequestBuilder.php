<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api;

use OctolizeShippingCanadaPostVendor\CanadaPost\ClientBase;
use OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Item;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Package;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Weight;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\UnitConversion\UniversalDimension;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Exception\TooManyPackagesException;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings;
/**
 * Build request for Canada Post rate
 */
class CanadaPostRateRequestBuilder
{
    const DIMENSIONS_ROUNDING_PRECISION = 1;
    /**
     * WooCommerce shipment.
     *
     * @var Shipment
     */
    protected $shipment;
    /**
     * Settings values.
     *
     * @var SettingsValues
     */
    protected $settings;
    /**
     * Request
     *
     * @var Rating
     */
    protected $request;
    /**
     * Shop settings.
     *
     * @var ShopSettings
     */
    protected $shop_settings;
    /**
     * CabadaPostRateRequestBuilder constructor.
     *
     * @param SettingsValues $settings Settings.
     * @param Shipment $shipment Shipment.
     * @param ShopSettings $helper Helper.
     * @param LoggerInterface $logger Logger.
     */
    public function __construct(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Shipment $shipment, \OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\ShopSettings $helper, \OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->settings = $settings;
        $this->shipment = $shipment;
        $this->shop_settings = $helper;
        $this->request = $this->prepare_rate_request($logger);
    }
    /**
     * Prepare rate request.
     *
     * @return Rating
     */
    protected function prepare_rate_request(\OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface $logger) : \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\Rating
    {
        $config = ['env' => \OctolizeShippingCanadaPostVendor\CanadaPost\ClientBase::ENV_PRODUCTION, 'username' => $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::USERNAME, ''), 'password' => $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::PASSWORD, ''), 'customer_number' => $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::CUSTOMER_NUMBER, '')];
        $rating = new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\Rating($config);
        $rating->setLogger($logger);
        return $rating;
    }
    /**
     * Calculate package weight.
     *
     * @param Package $shipment_package .
     * @param string $weight_unit .
     *
     * @return float
     * @throws UnitConversionException Weight exception.
     */
    protected function calculate_package_weight(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package, $weight_unit) : float
    {
        $package_weight = 0.0;
        foreach ($shipment_package->items as $item) {
            $item_weight = (new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\UnitConversion\UniversalWeight($item->weight->weight, $item->weight->weight_unit))->as_unit_rounded($weight_unit, 3);
            $package_weight += $item_weight;
        }
        return $package_weight === 0.0 ? (float) $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::PACKAGE_WEIGHT, 0.0) : $package_weight;
    }
    /**
     * Calculate package value.
     *
     * @param Package $shipment_package .
     *
     * @return float
     */
    protected function calculate_package_value(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package) : float
    {
        $total_value = 0.0;
        /** @var Item $item */
        // phpcs:ignore
        foreach ($shipment_package->items as $item) {
            $total_value += $item->declared_value->amount;
        }
        return $total_value;
    }
    /**
     * Add package.
     *
     * @param Package $shipment_package .
     *
     * @throws UnitConversionException .
     */
    protected function add_package(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Package $shipment_package)
    {
        $this->request->set_package_weight($this->calculate_package_weight($shipment_package, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Weight::WEIGHT_UNIT_KG));
        $this->request->set_package_value($this->calculate_package_value($shipment_package));
        if ($shipment_package->dimensions) {
            $width = new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\UnitConversion\UniversalDimension($shipment_package->dimensions->width, $shipment_package->dimensions->dimensions_unit);
            $height = new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\UnitConversion\UniversalDimension($shipment_package->dimensions->height, $shipment_package->dimensions->dimensions_unit);
            $length = new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\UnitConversion\UniversalDimension($shipment_package->dimensions->length, $shipment_package->dimensions->dimensions_unit);
            $this->request->set_package_height($height->as_unit_rounded(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions::DIMENSION_UNIT_CM, self::DIMENSIONS_ROUNDING_PRECISION));
            $this->request->set_package_width($width->as_unit_rounded(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions::DIMENSION_UNIT_CM, self::DIMENSIONS_ROUNDING_PRECISION));
            $this->request->set_package_length($length->as_unit_rounded(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions::DIMENSION_UNIT_CM, self::DIMENSIONS_ROUNDING_PRECISION));
        } else {
            $this->request->set_package_height(\round((float) $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::PACKAGE_HEIGHT, 0.0), self::DIMENSIONS_ROUNDING_PRECISION));
            $this->request->set_package_width(\round((float) $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::PACKAGE_WIDTH, 0.0), self::DIMENSIONS_ROUNDING_PRECISION));
            $this->request->set_package_length(\round((float) $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::PACKAGE_LENGTH, 0.0), self::DIMENSIONS_ROUNDING_PRECISION));
        }
    }
    /**
     * Set package;
     *
     * @throws UnitConversionException Weight exception.
     */
    protected function set_packages()
    {
        if (\count($this->shipment->packages) > 1) {
            throw new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Exception\TooManyPackagesException(\__('Too many packages in shipment!', 'octolize-canada-post-shipping'));
        }
        foreach ($this->shipment->packages as $package) {
            $this->add_package($package);
        }
    }
    /**
     * Build request.
     *
     * @throws UnitConversionException Weight exception.
     */
    public function build_request()
    {
        $this->set_packages();
        if ('yes' === $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::INSURANCE, 'no')) {
            $this->request->set_insurance(\true);
        }
        $this->request->set_origin_postal_code($this->shipment->ship_from->address->postal_code);
        $this->request->set_country_code($this->shipment->ship_to->address->country_code);
        $this->request->set_postal_code($this->shipment->ship_to->address->postal_code);
        $this->request->set_quote_type($this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::QUOTE_TYPE, 'commercial'));
    }
    /**
     * Get request.
     *
     * @return Rating
     */
    public function get_build_request()
    {
        return $this->request;
    }
}
