<?php
declare(strict_types = 1);

namespace Tests\Unit\Factories;

use PHPUnit\Framework\TestCase;
use SignNow\Rest\EntityManager;
use SignNow\Rest\Factories\AnnotationResolverFactory;
use SignNow\Rest\Factories\ClientFactory;
use SignNow\Rest\Factories\EntityManagerFactory;
use SignNow\Rest\Factories\SerializerBuilderFactory;

/**
 * Class EntityManagerFactoryTest
 *
 * @package Tests\Unit\Factories
 */
class EntityManagerFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreate(): void
    {
        $factory = new EntityManagerFactory(
            new ClientFactory([]),
            new SerializerBuilderFactory(),
            new AnnotationResolverFactory()
        );
        
        $this->assertInstanceOf(EntityManager::class, $factory->create());
    }
}
