<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace OctolizeShippingCostOnProductPageVendor\Monolog\Handler;

use OctolizeShippingCostOnProductPageVendor\Monolog\Logger;
use OctolizeShippingCostOnProductPageVendor\Monolog\Formatter\NormalizerFormatter;
use OctolizeShippingCostOnProductPageVendor\Doctrine\CouchDB\CouchDBClient;
/**
 * CouchDB handler for Doctrine CouchDB ODM
 *
 * @author Markus Bachmann <markus.bachmann@bachi.biz>
 */
class DoctrineCouchDBHandler extends \OctolizeShippingCostOnProductPageVendor\Monolog\Handler\AbstractProcessingHandler
{
    private $client;
    public function __construct(\OctolizeShippingCostOnProductPageVendor\Doctrine\CouchDB\CouchDBClient $client, $level = \OctolizeShippingCostOnProductPageVendor\Monolog\Logger::DEBUG, $bubble = \true)
    {
        $this->client = $client;
        parent::__construct($level, $bubble);
    }
    /**
     * {@inheritDoc}
     */
    protected function write(array $record)
    {
        $this->client->postDocument($record['formatted']);
    }
    protected function getDefaultFormatter()
    {
        return new \OctolizeShippingCostOnProductPageVendor\Monolog\Formatter\NormalizerFormatter();
    }
}