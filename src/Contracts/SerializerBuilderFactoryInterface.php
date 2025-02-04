<?php
declare(strict_types = 1);

namespace SignNow\Rest\Contracts;

use JMS\Serializer\SerializerBuilder;

interface SerializerBuilderFactoryInterface
{
    public function create(): SerializerBuilder;
}
