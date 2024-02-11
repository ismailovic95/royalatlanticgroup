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
namespace OctolizeShippingCanadaPostVendor\Monolog\Test;

use OctolizeShippingCanadaPostVendor\Monolog\Logger;
use OctolizeShippingCanadaPostVendor\Monolog\DateTimeImmutable;
use OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface;
/**
 * Lets you easily generate log records and a dummy formatter for testing purposes
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 *
 * @phpstan-import-type Record from \Monolog\Logger
 * @phpstan-import-type Level from \Monolog\Logger
 *
 * @internal feel free to reuse this to test your own handlers, this is marked internal to avoid issues with PHPStorm https://github.com/Seldaek/monolog/issues/1677
 */
class TestCase extends \OctolizeShippingCanadaPostVendor\PHPUnit\Framework\TestCase
{
    public function tearDown() : void
    {
        parent::tearDown();
        if (isset($this->handler)) {
            unset($this->handler);
        }
    }
    /**
     * @param mixed[] $context
     *
     * @return array Record
     *
     * @phpstan-param  Level $level
     * @phpstan-return Record
     */
    protected function getRecord(int $level = \OctolizeShippingCanadaPostVendor\Monolog\Logger::WARNING, string $message = 'test', array $context = []) : array
    {
        return ['message' => (string) $message, 'context' => $context, 'level' => $level, 'level_name' => \OctolizeShippingCanadaPostVendor\Monolog\Logger::getLevelName($level), 'channel' => 'test', 'datetime' => new \OctolizeShippingCanadaPostVendor\Monolog\DateTimeImmutable(\true), 'extra' => []];
    }
    /**
     * @phpstan-return Record[]
     */
    protected function getMultipleRecords() : array
    {
        return [$this->getRecord(\OctolizeShippingCanadaPostVendor\Monolog\Logger::DEBUG, 'debug message 1'), $this->getRecord(\OctolizeShippingCanadaPostVendor\Monolog\Logger::DEBUG, 'debug message 2'), $this->getRecord(\OctolizeShippingCanadaPostVendor\Monolog\Logger::INFO, 'information'), $this->getRecord(\OctolizeShippingCanadaPostVendor\Monolog\Logger::WARNING, 'warning'), $this->getRecord(\OctolizeShippingCanadaPostVendor\Monolog\Logger::ERROR, 'error')];
    }
    protected function getIdentityFormatter() : \OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface
    {
        $formatter = $this->createMock(\OctolizeShippingCanadaPostVendor\Monolog\Formatter\FormatterInterface::class);
        $formatter->expects($this->any())->method('format')->will($this->returnCallback(function ($record) {
            return $record['message'];
        }));
        return $formatter;
    }
}
