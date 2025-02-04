<?php
declare(strict_types = 1);

namespace SignNow\Rest\Factories;

use SignNow\Rest\Contracts\AnnotationResolverFactoryInterface;
use SignNow\Rest\Contracts\ClientFactoryInterface;
use SignNow\Rest\Contracts\PoolFactoryInterface;
use SignNow\Rest\Contracts\SerializerBuilderFactoryInterface;
use SignNow\Rest\EntityManager;

/**
 * Class EntityManagerFactory
 *
 * @package SignNow\Rest\Factories
 */
class EntityManagerFactory
{
    /**
     * @var SerializerBuilderFactoryInterface
     */
    private $builderFactory;
    
    /**
     * @var ClientFactoryInterface
     */
    private $clientFactory;
    
    /**
     * @var AnnotationResolverFactoryInterface
     */
    private $annotationResolverFactory;
    
    /**
     * @var PoolFactoryInterface
     */
    private $poolFactory;

    /**
     * EntityManagerFactory constructor.
     *
     * @param ClientFactoryInterface $clientFactory
     * @param SerializerBuilderFactoryInterface|null $builderFactory
     * @param AnnotationResolverFactoryInterface|null $annotationResolverFactory
     * @param PoolFactoryInterface|null $poolFactory
     */
    public function __construct(
        ClientFactoryInterface $clientFactory,
        SerializerBuilderFactoryInterface $builderFactory = null,
        AnnotationResolverFactoryInterface $annotationResolverFactory = null,
        PoolFactoryInterface $poolFactory = null
    ) {
        $this->clientFactory = $clientFactory;
        $this->builderFactory = $builderFactory ?: new SerializerBuilderFactory();
        $this->annotationResolverFactory = $annotationResolverFactory ?: new AnnotationResolverFactory();
        $this->poolFactory = $poolFactory ?: new PoolFactory();
    }
    
    /**
     * @return EntityManager
     */
    public function create(): EntityManager
    {
        $client = $this->clientFactory->create();
        
        return new EntityManager(
            $client,
            $this->builderFactory->create()->build(),
            $this->annotationResolverFactory->create(),
            $this->poolFactory->create($client)
        );
    }
}
