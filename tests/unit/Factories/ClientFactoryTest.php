<?php
declare(strict_types = 1);

namespace Tests\Unit\Factories;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use SignNow\Rest\Factories\ClientFactory;

/**
 * Class ClientFactoryTest
 *
 * @coversDefaultClass \SignNow\Rest\Factories\ClientFactory
 *
 * @uses \SignNow\Rest\Factories\HandlerFactory
 * @uses \GuzzleHttp\HandlerStack
 *
 * @package Tests\Unit\Factories
 */
class ClientFactoryTest extends TestCase
{
    /**
     * @covers ::create
     * @covers ::__construct
     */
    public function testCreate(): void
    {
        $middleware = function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                return $handler($request, $options);
            };
        };
        $factory = new ClientFactory([],[$middleware]);
        
        $this->assertInstanceOf(ClientInterface::class, $factory->create());
    }
}
