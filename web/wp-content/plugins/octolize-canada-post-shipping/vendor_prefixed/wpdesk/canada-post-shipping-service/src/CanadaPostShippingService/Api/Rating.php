<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api;

use OctolizeShippingCanadaPostVendor\CanadaPost\Exception\ClientException;
use OctolizeShippingCanadaPostVendor\GuzzleHttp\Psr7\Response;
use OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface;
use OctolizeShippingCanadaPostVendor\Spatie\ArrayToXml\ArrayToXml;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Exception\ApiResponseException;
class Rating extends \OctolizeShippingCanadaPostVendor\CanadaPost\Rating
{
    /**
     * @var string
     */
    private $origin_postal_code;
    /**
     * @var string
     */
    private $postal_code;
    /**
     * @var string
     */
    private $country_code;
    /**
     * @var float
     */
    private $package_weight = 0.0;
    /**
     * @var float
     */
    private $package_length = 0.0;
    /**
     * @var float
     */
    private $package_width = 0.0;
    /**
     * @var float
     */
    private $package_height = 0.0;
    /**
     * @var bool
     */
    private $insurance = \false;
    /**
     * @var float
     */
    private $package_value = 0.0;
    /**
     * @var string
     */
    private $quote_type = 'commercial';
    /**
     * @var LoggerInterface
     */
    private $logger;
    public function setLogger(\OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * @param string $origin_postal_code
     */
    public function set_origin_postal_code($origin_postal_code)
    {
        $this->origin_postal_code = $origin_postal_code;
    }
    /**
     * @param string $country_code
     */
    public function set_country_code($country_code)
    {
        $this->country_code = $country_code;
    }
    /**
     * @param string $postal_code
     */
    public function set_postal_code($postal_code)
    {
        $this->postal_code = $postal_code;
    }
    /**
     * @param float $package_weight
     */
    public function set_package_weight($package_weight)
    {
        $this->package_weight = $package_weight;
    }
    /**
     * @param float $package_length
     */
    public function set_package_length(float $package_length)
    {
        $this->package_length = $package_length;
    }
    /**
     * @param float $package_width
     */
    public function set_package_width(float $package_width)
    {
        $this->package_width = $package_width;
    }
    /**
     * @param float $package_height
     */
    public function set_package_height(float $package_height)
    {
        $this->package_height = $package_height;
    }
    /**
     * @param bool $insurance
     */
    public function set_insurance($insurance)
    {
        $this->insurance = $insurance;
    }
    /**
     * @param float $package_value
     */
    public function set_package_value($package_value)
    {
        $this->package_value = $package_value;
    }
    /**
     * @param string $quote_type
     */
    public function set_quote_type(string $quote_type)
    {
        $this->quote_type = $quote_type;
    }
    /**
     * Get the shipping rates for the given locations and weight.
     *
     * @return \DOMDocument
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRate()
    {
        $origin_postal_code = \strtoupper(\str_replace(' ', '', $this->origin_postal_code));
        $content = ['parcel-characteristics' => ['weight' => $this->package_weight], 'origin-postal-code' => $origin_postal_code, 'destination' => $this->prepare_destination()];
        if ($this->package_height || $this->package_length || $this->package_width) {
            $content['parcel-characteristics']['dimensions'] = ['length' => $this->package_length, 'width' => $this->package_width, 'height' => $this->package_height];
        }
        $content['quote-type'] = $this->quote_type;
        if ('commercial' === $this->quote_type) {
            $content['customer-number'] = $this->customerNumber;
        }
        $options = $this->prepare_options();
        if ($options) {
            $content['options'] = $options;
        }
        $xml = new \DOMDocument();
        $xml->loadXML(\OctolizeShippingCanadaPostVendor\Spatie\ArrayToXml\ArrayToXml::convert($content, 'mailing-scenario'));
        $envelope = $xml->documentElement;
        $envelope->setAttribute('xmlns', 'http://www.canadapost.ca/ws/ship/rate-v4');
        $xml->formatOutput = \true;
        $payload = $xml->saveXML();
        $this->logger->debug('Request to Canada Post API', ['content' => $payload, 'endpointurl' => 'https://soa-gw.canadapost.ca/rs/ship/price', 'timestamp' => \date('c')]);
        try {
            $response = $this->post("rs/ship/price", ['Content-Type' => 'application/vnd.cpc.ship.rate-v4+xml', 'Accept' => 'application/vnd.cpc.ship.rate-v4+xml'], $payload);
            $response->formatOutput = \true;
            $this->logger->debug('Response from Canada Post API', ['content' => $response->saveXML(), 'timestamp' => \date('c')]);
        } catch (\OctolizeShippingCanadaPostVendor\CanadaPost\Exception\ClientException $e) {
            $response_body = $e->getResponseBody();
            if ($response_body instanceof \DOMDocument) {
                $response = $response_body;
                $response->formatOutput = \true;
                $content = $response->saveXML();
            } else {
                $response = new \DOMDocument();
                if ($response->loadXML($response_body)) {
                    $response->formatOutput = \true;
                    $content = $response->saveXML();
                } else {
                    $content = $response_body;
                    $response = null;
                }
            }
            $this->logger->debug('Response from Canada Post API', ['content' => $content, 'timestamp' => \date('c')]);
            $this->throw_exception($response);
        }
        return $response;
    }
    /**
     * @param \DOMDocument|string $response .
     *
     * @return mixed
     */
    private function throw_exception($response)
    {
        if (!$response instanceof \DOMDocument) {
            $message = \sprintf(\__('API returns invalid response: %s', 'octolize-canada-post-shipping'), \print_r($response, \true));
        } else {
            $message = '';
            if ($response->getElementsByTagName('messages')) {
                /** @var \DOMElement $message_node */
                foreach ($response->getElementsByTagName('messages') as $message_node) {
                    if ($message_node->getElementsByTagName('description')) {
                        $message .= $message_node->getElementsByTagName('description')[0]->nodeValue . ' ';
                    }
                }
            }
            if (!$message) {
                $message = \sprintf(\__('API returns invalid response: %s', 'octolize-canada-post-shipping'), $response->saveXML());
            }
        }
        throw new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Exception\ApiResponseException($message);
    }
    private function prepare_options()
    {
        $options = [];
        if ($this->insurance) {
            $options['option'] = [['option-code' => 'COV', 'option-amount' => $this->package_value]];
        }
        return $options;
    }
    private function prepare_destination()
    {
        $postalCode = \strtoupper(\str_replace(' ', '', $this->postal_code));
        if ('CA' === $this->country_code) {
            $destination = ['domestic' => ['postal-code' => $postalCode]];
        } elseif ('US' === $this->country_code) {
            $destination = ['united-states' => ['zip-code' => $postalCode]];
        } else {
            $destination = ['international' => ['postal-code' => $postalCode, 'country-code' => $this->country_code]];
        }
        return $destination;
    }
    /**
     * Parse the xml response into an array,
     *
     * @param Response $response
     *
     * @return \DOMDocument
     * @throws \Exception
     */
    protected function parseResponse(\OctolizeShippingCanadaPostVendor\GuzzleHttp\Psr7\Response $response)
    {
        $xml = new \DOMDocument();
        $xml->loadXML($response->getBody());
        return $xml;
    }
}
