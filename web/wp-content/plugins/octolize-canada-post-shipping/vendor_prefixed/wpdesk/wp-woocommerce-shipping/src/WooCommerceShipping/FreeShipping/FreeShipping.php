<?php

/**
 * Free Shipping.
 *
 * @package WPDesk\WooCommerceShipping\FreeShipping
 */
namespace OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\FreeShipping;

use OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface;
use WC_Cart;
use WC_Shipping_Method;
use OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\DisplayNoticeLogger;
/**
 * Can apply handling fees to price.
 */
class FreeShipping
{
    /**
     * @var WC_Shipping_Method .
     */
    private $shipping_method;
    /**
     * @var DisplayNoticeLogger .
     */
    private $logger;
    /**
     * FreeShipping constructor.
     *
     * @param WC_Shipping_Method  $shipping_method .
     * @param LoggerInterface $logger
     */
    public function __construct($shipping_method, $logger)
    {
        $this->shipping_method = $shipping_method;
        $this->logger = $logger;
    }
    /**
     * Check can apply free shipping.
     *
     * @param float $subtotal Input Cart.
     *
     * @return bool
     */
    public function can_apply($subtotal)
    {
        return $subtotal >= $this->get_free_shipping_amount();
    }
    /**
     * @param bool  $is_applied .
     * @param float $subtotal   .
     */
    public function debug($is_applied, $subtotal)
    {
        $label = $is_applied ? \__('Cart value exceeds %s. Free shipping has been applied.', 'octolize-canada-post-shipping') : \__('Cart value doesn\'t exceed %s. Free shipping hasn\'t been applied.', 'octolize-canada-post-shipping');
        $this->logger->debug(\sprintf($label, \wc_price($this->get_free_shipping_amount())), array(\__('Subtotal', 'octolize-canada-post-shipping') => $subtotal, \__('Free Shipping Amount', 'octolize-canada-post-shipping') => $this->get_free_shipping_amount()));
    }
    /**
     * @return bool
     */
    public function is_enabled()
    {
        return 'yes' === $this->shipping_method->get_option(\OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShippingFields::FIELD_STATUS, 'no');
    }
    /**
     * @return float
     */
    private function get_free_shipping_amount() : float
    {
        return $this->get_free_shipping_amount_converted_to_current_currency((float) \str_replace(',', '.', $this->shipping_method->get_option(\OctolizeShippingCanadaPostVendor\WPDesk\WooCommerceShipping\FreeShipping\FreeShippingFields::FIELD_AMOUNT)));
    }
    private function get_free_shipping_amount_converted_to_current_currency(float $amount) : float
    {
        return (float) \apply_filters($this->shipping_method->id . '/currency-switchers/amount', $amount, $this->logger);
    }
}
