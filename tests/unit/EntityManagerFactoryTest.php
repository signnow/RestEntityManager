<?php
declare(strict_types = 1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use SignNow\Rest\EntityManager;
use SignNow\Rest\EntityManagerFactory;

/**
 * Class EntityManagerFactoryTest
 *
 * @package Tests\Unit
 *
 * @coversDefaultClass \SignNow\Rest\EntityManagerFactory
 *
 * @uses \SignNow\Rest\EntityManager
 * @uses \SignNow\Rest\EntityManager\AnnotationResolver
 * @uses \SignNow\Rest\Service\Request\Pool
 * @uses \SignNow\Rest\Service\Serializer\Handler\File
 */
class EntityManagerFactoryTest extends TestCase
{
    /**
     * @throws \Doctrine\Common\Annotations\AnnotationException
     *
     * @covers ::createEntityManager
     */
    public function testCreateEntity(): void
    {
        $factory = new EntityManagerFactory();
        
        $this->assertInstanceOf(EntityManager::class, $factory->createEntityManager([]));
    }
}


