<?php
declare(strict_types = 1);

namespace SignNow\Rest\Factories;

use SignNow\Rest\EntityManager;

/**
 * Class EntityManagerFactory
 *
 * @package SignNow\Rest\Factories
 */
class EntityManagerFactory
{
    /**
     * @var SerializerBuilderFactory
     */
    private $builderFactory;
    
    /**
     * @var ClientFactory
     */
    private $clientFactory;
    
    /**
     * @var AnnotationResolverFactory
     */
    private $annotationResolverFactory;
    
    /**
     * @var PoolFactory
     */
    private $poolFactory;
    
    /**
     * EntityManagerFactory constructor.
     *
     * @param SerializerBuilderFactory  $builderFactory
     * @param ClientFactory             $clientFactory
     * @param AnnotationResolverFactory $annotationResolverFactory
     * @param PoolFactory               $poolFactory
     */
    public function __construct(
        ClientFactory $clientFactory,
        SerializerBuilderFactory $builderFactory = null,
        AnnotationResolverFactory $annotationResolverFactory = null,
        PoolFactory $poolFactory = null
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
