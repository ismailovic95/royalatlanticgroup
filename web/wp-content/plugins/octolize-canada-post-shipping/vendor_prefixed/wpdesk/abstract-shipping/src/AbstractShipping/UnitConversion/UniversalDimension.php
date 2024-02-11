<?php

/**
 * UnitConversion: Lenght Conversion.
 *
 * @package WPDesk\AbstractShipping\Shipment
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\UnitConversion;

use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\UnitConversionException;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Weight;
/**
 * Can convert length between different measure types
 */
class UniversalDimension
{
    private $unit_calc = [\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions::DIMENSION_UNIT_IN => 25.4, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions::DIMENSION_UNIT_MM => 1, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions::DIMENSION_UNIT_CM => 10, \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Shipment\Dimensions::DIMENSION_UNIT_M => 1000];
    /**
     * Length.
     *
     * @var float
     */
    private $length;
    /**
     * Precision.
     *
     * @var int
     */
    private $precision;
    /**
     * LengthConverter constructor.
     *
     * @param float $length Length.
     * @param string $from_unit From unit.
     * @param int $precision .
     *
     * @throws UnitConversionException Dimension exception.
     */
    public function __construct($length, $from_unit, $precision = 3)
    {
        $this->precision = $precision;
        $this->length = $this->to_mm($length, $from_unit);
    }
    /**
     * @param float $length .
     * @param string $unit .
     *
     * @return false|float
     * @throws UnitConversionException
     */
    private function to_mm($length, $unit)
    {
        $unit = \strtoupper($unit);
        if (isset($this->unit_calc[$unit])) {
            $calc = $this->unit_calc[$unit];
            return \round($length * $calc, $this->precision);
        }
        throw new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\UnitConversionException(\sprintf('Can\'t support "%s" unit', $unit));
    }
    /**
     * Convert to target unit. Returns 0 if confused.
     *
     * @param string $to_unit Target unit.
     * @param int $precision .
     *
     * @return float
     *
     * @throws UnitConversionException Dimension exception.
     */
    public function as_unit_rounded($to_unit, $precision = 2)
    {
        $to_unit = \strtoupper($to_unit);
        if (isset($this->unit_calc[$to_unit])) {
            $calc = $this->unit_calc[$to_unit];
            return \round($this->length / $calc, $precision);
        }
        throw new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Exception\UnitConversionException(\__('Can\'t convert weight to target unit.', 'octolize-canada-post-shipping'));
    }
}
