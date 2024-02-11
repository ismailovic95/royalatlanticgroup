<?php

namespace OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api;

use OctolizeShippingCanadaPostVendor\CanadaPost\ClientBase;
use OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues;
use OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValuesAsArray;
use OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition;
/**
 * Can check connection.
 */
class ConnectionChecker
{
    /**
     * Settings.
     *
     * @var SettingsValuesAsArray
     */
    private $settings;
    /**
     * Logger.
     *
     * @var LoggerInterface
     */
    private $logger;
    /**
     * ConnectionChecker constructor.
     *
     * @param SettingsValues      $settings .
     * @param LoggerInterface     $logger .
     */
    public function __construct(\OctolizeShippingCanadaPostVendor\WPDesk\AbstractShipping\Settings\SettingsValues $settings, \OctolizeShippingCanadaPostVendor\Psr\Log\LoggerInterface $logger)
    {
        $this->settings = $settings;
        $this->logger = $logger;
    }
    /**
     * Pings API.
     *
     * @throws \Exception .
     */
    public function check_connection()
    {
        $this->logger->debug('Connection checker', ['source' => 'canadapost']);
        try {
            $config = ['env' => \OctolizeShippingCanadaPostVendor\CanadaPost\ClientBase::ENV_PRODUCTION, 'username' => $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::USERNAME, ''), 'password' => $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::PASSWORD, ''), 'customer_number' => $this->settings->get_value(\OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\CanadaPostSettingsDefinition::CUSTOMER_NUMBER, '')];
            $rating_service = new \OctolizeShippingCanadaPostVendor\WPDesk\CanadaPostShippingService\Api\Rating($config);
            $rating_service->setLogger($this->logger);
            $rating_service->set_postal_code('K0H 9Z9');
            $rating_service->set_origin_postal_code('K0H 9Z9');
            $rating_service->set_country_code('CA');
            $rating_service->set_package_weight(1);
            $rates = $rating_service->getRate();
            $this->logger->debug('Connection success', ['source' => 'canadapost', 'rates' => $rates]);
        } catch (\Exception $e) {
            $this->logger->debug(' Connection checker error', ['source' => 'canadapost', 'error' => $e->getMessage()]);
            throw $e;
        }
    }
}
