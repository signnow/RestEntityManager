<?php
declare(strict_types = 1);

namespace Tests\Unit\EntityManager;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SignNow\Rest\EntityManager\AnnotationFactory;

/**
 * Class AnnotationFactoryTest
 *
 * @coversDefaultClass \SignNow\Rest\EntityManager\AnnotationFactory
 *
 * @package Tests\Unit\EntityManager
 */
class AnnotationFactoryTest extends TestCase
{
    /**
     * @covers ::createDefaultAnnotation
     */
    public function testInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        $factory = new AnnotationFactory();
        
        $factory->createDefaultAnnotation('invalidAnnotationType');
    }    
}
