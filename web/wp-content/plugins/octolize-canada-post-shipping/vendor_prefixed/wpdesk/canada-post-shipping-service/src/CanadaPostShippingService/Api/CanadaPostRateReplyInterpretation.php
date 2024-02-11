<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api;

use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\Money;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\SingleRate;
/**
 * Get response from API.
 */
class CanadaPostRateReplyInterpretation
{
    /**
     * Should remove tax from response.
     *
     * @var bool
     */
    protected $remove_tax;
    /**
     * Reply.
     *
     * @var \DOMDocument
     */
    protected $response;
    /**
     * CanadaPostRateReplyInterpretation constructor.
     *
     * @param \DOMDocument $response Rate request.
     * @param bool $remove_tax Should remove tax from response.
     */
    public function __construct($response, $remove_tax)
    {
        $this->response = $response;
        $this->remove_tax = $remove_tax;
    }
    /**
     * Has reply error.
     *
     * @return bool
     */
    public function has_reply_error()
    {
        return \false;
    }
    /**
     * Has reply warning.
     *
     * @return bool
     */
    public function has_reply_warning()
    {
        return \false;
    }
    /**
     * Get reply error message.
     *
     * @return string
     */
    public function get_reply_message()
    {
        return '';
    }
    /**
     * Get reates from Canada Post.
     *
     * @return SingleRate[]
     */
    public function get_rates()
    {
        $rates = array();
        $price_quotes = $this->response->getElementsByTagName('price-quote');
        /** @var \DOMElement $price_quote */
        foreach ($price_quotes as $price_quote) {
            $single_rate = new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\SingleRate();
            $single_rate->service_type = $price_quote->getElementsByTagName('service-code')[0]->nodeValue;
            $single_rate->service_name = $price_quote->getElementsByTagName('service-name')[0]->nodeValue;
            $single_rate->total_charge = new \OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Rate\Money();
            $total_charge_amount = $price_quote->getElementsByTagName('price-details')[0]->getElementsByTagName('due')[0]->nodeValue;
            if ($this->remove_tax) {
                $total_charge_amount -= $this->get_tax_value_from_price_quote($price_quote->getElementsByTagName('price-details')[0]->getElementsByTagName('taxes')[0]);
            }
            $single_rate->total_charge->amount = $total_charge_amount;
            $single_rate->total_charge->currency = 'CAD';
            $rates[] = $single_rate;
        }
        return $rates;
    }
    /**
     * @param \DOMElement $taxes
     *
     * @return float
     */
    private function get_tax_value_from_price_quote(\DOMElement $taxes)
    {
        $tax_value = 0.0;
        foreach ($taxes->childNodes as $tax) {
            $tax_value += (float) $tax->nodeValue;
        }
        return $tax_value;
    }
}
