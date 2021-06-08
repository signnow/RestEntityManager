<?php
declare(strict_types = 1);

namespace SignNow\Rest;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlMultiHandler;
use GuzzleHttp\HandlerStack;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use SignNow\Rest\Service\Serializer\Handler\File;
use SignNow\Rest\Service\Serializer\Handler\SplFileInfo;
use SignNow\Rest\Service\Serializer\Handler\FileLink;

/**
 * Class EntityManagerFactory
 *
 * @package SignNow\Rest
 * @deprecated Please use SignNow\Rest\Factories\EntityManagerFactory instead of this class
 */
class EntityManagerFactory
{
    /**
     * @param array $clientOptions
     *
     * @return EntityManager
     * @throws AnnotationException
     */
    public function createEntityManager(array $clientOptions): EntityManager
    {
        $stack = HandlerStack::create();
        $stack->setHandler(new CurlMultiHandler());
        
        $defaultClientOptions = [
            'handler' => $stack,
        ];
        $client = new Client(array_merge($defaultClientOptions, $clientOptions));
        
        $builder = SerializerBuilder::create();
        $builder->addDefaultSerializationVisitors();
        $builder->addDefaultDeserializationVisitors();
        $builder->configureHandlers(function (HandlerRegistry $registry) {
            $registry->registerSubscribingHandler(new SplFileInfo());
            $registry->registerSubscribingHandler(new FileLink());
            $registry->registerSubscribingHandler(new File());
        });
        
        $resolver = new EntityManager\AnnotationResolver(new AnnotationReader(), new EntityManager\AnnotationFactory());
        
        return new EntityManager($client, $builder->build(), $resolver);
    }
}
