<?php
declare(strict_types = 1);

namespace SignNow\Rest\Factories;

use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SignNow\Rest\Service\Serializer\Handler\File;
use SignNow\Rest\Service\Serializer\Handler\FileLink;
use SignNow\Rest\Service\Serializer\Handler\SplFileInfo;

/**
 * Class BuilderFactory
 *
 * @package SignNow\Rest\Factories
 */
class SerializerBuilderFactory
{
    /**
     * @return SerializerBuilder
     */
    public function create(): SerializerBuilder
    {
        $builder = SerializerBuilder::create();
        $builder->addDefaultSerializationVisitors();
        $builder->addDefaultDeserializationVisitors();
        $builder->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new SplFileInfo());
            $registry->registerSubscribingHandler(new FileLink());
            $registry->registerSubscribingHandler(new File());
        });
        
        return $builder;
    }
}
