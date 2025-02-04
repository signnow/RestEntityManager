<?php
declare(strict_types = 1);

namespace SignNow\Rest\Contracts;

use GuzzleHttp\ClientInterface;

interface ClientFactoryInterface
{
    public function create(): ClientInterface;
}
