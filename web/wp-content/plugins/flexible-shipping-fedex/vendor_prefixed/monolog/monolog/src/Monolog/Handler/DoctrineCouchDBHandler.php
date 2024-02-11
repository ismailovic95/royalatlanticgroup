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
namespace FedExVendor\Monolog\Handler;

use FedExVendor\Monolog\Logger;
use FedExVendor\Monolog\Formatter\NormalizerFormatter;
use FedExVendor\Monolog\Formatter\FormatterInterface;
use FedExVendor\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \FedExVendor\Monolog\Handler\AbstractProcessingHandler
{
    /** @var CouchDBClient */
    private $client;
    public function __construct(\FedExVendor\Doctrine\CouchDB\CouchDBClient $client, $level = \FedExVendor\Monolog\Logger::DEBUG, bool $bubble = \true)
    {
        $this->client = $client;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record) : void
    {
        $this->client->postDocument($record['formatted']);
    }
    protected function getDefaultFormatter() : \FedExVendor\Monolog\Formatter\FormatterInterface
    {
        return new \FedExVendor\Monolog\Formatter\NormalizerFormatter();
    }
}