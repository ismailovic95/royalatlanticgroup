<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OctolizeShippingCanadaPostVendor\Monolog\Handler;

use OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface;
use OctolizeShippingCanadaPostVendor\Monolog\Formatter\NormalizerFormatter;
use OctolizeShippingCanadaPostVendor\Monolog\Logger;
/**
 * Handler sending logs to Zend Monitor
 *
 * @author  Christian Bergau <cbergau86@gmail.com>
 * @author  Jason Davis <happydude@jasondavis.net>
 *
 * @phpstan-import-type FormattedRecord from AbstractProcessingHandler
 */
class ZendMonitorHandler extends \OctolizeShippingCanadaPostVendor\Monolog\Handler\AbstractProcessingHandler
{
    /**
     * Monolog level / ZendMonitor Custom Event priority map
     *
     * @var array<int, int>
     */
    protected $levelMap = [];
    /**
     * @throws MissingExtensionException
     */
    public function __construct($level = \OctolizeShippingCanadaPostVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        if (!\function_exists('OctolizeShippingCanadaPostVendor\\zend_monitor_custom_event')) {
            throw new \OctolizeShippingCanadaPostVendor\Monolog\Handler\MissingExtensionException('You must have Zend Server installed with Zend Monitor enabled in order to use this handler');
        }
        //zend monitor constants are not defined if zend monitor is not enabled.
        $this->levelMap = [\OctolizeShippingCanadaPostVendor\Monolog\Logger::DEBUG => \OctolizeShippingCanadaPostVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \OctolizeShippingCanadaPostVendor\Monolog\Logger::INFO => \OctolizeShippingCanadaPostVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \OctolizeShippingCanadaPostVendor\Monolog\Logger::NOTICE => \OctolizeShippingCanadaPostVendor\ZEND_MONITOR_EVENT_SEVERITY_INFO, \OctolizeShippingCanadaPostVendor\Monolog\Logger::WARNING => \OctolizeShippingCanadaPostVendor\ZEND_MONITOR_EVENT_SEVERITY_WARNING, \OctolizeShippingCanadaPostVendor\Monolog\Logger::ERROR => \OctolizeShippingCanadaPostVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \OctolizeShippingCanadaPostVendor\Monolog\Logger::CRITICAL => \OctolizeShippingCanadaPostVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \OctolizeShippingCanadaPostVendor\Monolog\Logger::ALERT => \OctolizeShippingCanadaPostVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR, \OctolizeShippingCanadaPostVendor\Monolog\Logger::EMERGENCY => \OctolizeShippingCanadaPostVendor\ZEND_MONITOR_EVENT_SEVERITY_ERROR];
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        $this->writeZendMonitorCustomEvent(\OctolizeShippingCanadaPostVendor\Monolog\Logger::getLevelName($record['level']), $record['message'], $record['formatted'], $this->levelMap[$record['level']]);
    }
    /**
     * Write to Zend Monitor Events
     * @param string $type      Text displayed in "Class Name (custom)" field
     * @param string $message   Text displayed in "Error String"
     * @param array  $formatted Displayed in Custom Variables tab
     * @param int    $severity  Set the event severity level (-1,0,1)
     *
     * @phpstan-param FormattedRecord $formatted
     */
    protected function writeZendMonitorCustomEvent(string $type, string $message, array $formatted, int $severity) : void
    {
        zend_monitor_custom_event($type, $message, $formatted, $severity);
    }
    /**
     * {@inheritDoc}
     */
    public function getDefaultFormatter() : \OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface
    {
        return new \OctolizeShippingCanadaPostVendor\Monolog\Formatter\NormalizerFormatter();
    }
    /**
     * @return array<int, int>
     */
    public function getLevelMap() : array
    {
        return $this->levelMap;
    }
}
