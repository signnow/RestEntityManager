<?php
declare(strict_types = 1);

namespace Tests\Unit\Factories;

use PHPUnit\Framework\TestCase;
use SignNow\Rest\EntityManager\AnnotationResolver;
use SignNow\Rest\Factories\AnnotationResolverFactory;

/**
 * Class AnnotationResolverFactoryTest
 *
 * @coversDefaultClass \SignNow\Rest\Factories\AnnotationResolverFactory
 *
 * @package Tests\Unit\Factories
 */
class AnnotationResolverFactoryTest extends TestCase
{
    /**
     * @covers ::create
     * @covers ::__construct
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     */
    public function testCreate(): void
    {
        $factory = new AnnotationResolverFactory();
        
        $this->assertInstanceOf(AnnotationResolver::class, $factory->create());
    }
}
