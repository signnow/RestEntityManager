<?php
declare(strict_types = 1);

namespace Tests\Unit\Factories;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SignNow\Rest\Factories\PoolFactory;
use SignNow\Rest\Service\Request\Pool;

/**
 * Class PoolFactoryTest
 *
 * @package Tests\Unit\Factories
 */
class PoolFactoryTest extends TestCase
{
    /**
     * @throws ReflectionException
     *
     * @return void
     */
    public function testCreate(): void
    {
        $factory = new PoolFactory();
        $newClient = $this->getMockForAbstractClass(ClientInterface::class);
        
        $this->assertInstanceOf(Pool::class, $factory->create($newClient));
    }
}
