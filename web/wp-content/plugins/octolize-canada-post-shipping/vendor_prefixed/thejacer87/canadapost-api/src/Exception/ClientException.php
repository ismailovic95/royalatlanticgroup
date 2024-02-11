<?php

namespace OctolizeShippingCanadaPostVendor\CanadaPost\Exception;

use OctolizeShippingCanadaPostVendor\GuzzleHttp\Exception\RequestException;
use OctolizeShippingCanadaPostVendor\Psr\Http\Message\RequestInterface;
use OctolizeShippingCanadaPostVendor\Psr\Http\Message\ResponseInterface;
/**
 * Exception when a client error is encountered (4xx codes).
 *
 * In addition to the request and the response, it makes available the parsed
 * response body.
 */
class ClientException extends \OctolizeShippingCanadaPostVendor\GuzzleHttp\Exception\RequestException
{
    private $responseBody;
    public function __construct($message, $responseBody, \OctolizeShippingCanadaPostVendor\Psr\Http\Message\RequestInterface $request, \OctolizeShippingCanadaPostVendor\Psr\Http\Message\ResponseInterface $response = null, \Exception $previous = null, array $handlerContext = [])
    {
        $this->responseBody = $responseBody;
        parent::__construct($message, $request, $response, $previous, $handlerContext);
    }
    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
