<?php
declare(strict_types = 1);

namespace SignNow\Rest\Factories;

use GuzzleHttp\ClientInterface;
use SignNow\Rest\Contracts\PoolFactoryInterface;
use SignNow\Rest\Service\Request\Pool;

/**
 * Class PoolFactory
 *
 * @package SignNow\Rest\Factories
 */
class PoolFactory implements PoolFactoryInterface
{
    /**
     * @param ClientInterface $client
     *
     * @return Pool
     */
    public function create(ClientInterface $client): Pool
    {
        return new Pool($client);
    }
}
