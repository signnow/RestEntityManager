<?php
declare(strict_types = 1);

namespace SignNow\Rest\Contracts;

use GuzzleHttp\ClientInterface;
use SignNow\Rest\Service\Request\Pool;

interface PoolFactoryInterface
{
    public function create(ClientInterface $client): Pool;
}
