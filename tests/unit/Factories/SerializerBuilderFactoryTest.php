<?php
declare(strict_types = 1);

namespace Tests\Unit\Factories;

use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;
use SignNow\Rest\Factories\SerializerBuilderFactory;

/**
 * Class SerializerBuilderFactoryTest
 *
 * @package Tests\Unit\Factories
 */
class SerializerBuilderFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreate(): void
    {
        $factory = new SerializerBuilderFactory();
        
        $this->assertInstanceOf(SerializerBuilder::class, $factory->create());
    }
}
