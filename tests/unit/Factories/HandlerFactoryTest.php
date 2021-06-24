<?php
declare(strict_types = 1);

namespace Tests\Unit\Factories;

use GuzzleHttp\Handler\CurlMultiHandler;
use PHPUnit\Framework\TestCase;
use SignNow\Rest\Factories\HandlerFactory;

/**
 * Class HandlerFactoryTest
 *
 * @package Tests\Unit\Factories
 */
class HandlerFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testCreate(): void
    {
        $factory = new HandlerFactory();
        
        $this->assertInstanceOf(CurlMultiHandler::class, $factory->create());
    }
}
