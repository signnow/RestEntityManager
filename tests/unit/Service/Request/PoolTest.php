<?php
declare(strict_types = 1);

namespace Tests\Unit\Service\Request;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\RejectedPromise;
use GuzzleHttp\Psr7\Response;
use LogicException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use SignNow\Rest\EntityManager\Exception\PoolException;
use SignNow\Rest\Factories\ClientFactory;
use SignNow\Rest\Service\Request\Pool;
use SignNow\Rest\Service\Request\Pool\Item;
use Tests\Fixtures\Entity\FileEntity;

/**
 * Class PoolTest
 *
 * @package Tests\Unit\Service\Request
 */
class PoolTest extends TestCase
{
    /**
     * @throws ReflectionException
     * @throws PoolException
     */
    public function testExceptionOnEmptyPoolSend(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Please add items to request pool first');
        
        $client = $this->getMockForAbstractClass(ClientInterface::class);
        
        $pool = new Pool($client);
        $pool->send();
    }
    
    /**
     * @throws ReflectionException
     */
    public function testSuccessSend(): void
    {
        $response = 'some response';
        $client = $this->getMockForAbstractClass(ClientInterface::class);
    
        $pool = new Pool($client);
        $item = (new Item())
            ->setClosure(function () use ($response) {
                return new Response(200, [], $response);
            })
            ->setEntity(new FileEntity());
        
        $pool->add($item)->send(10);
        
        $this->assertEquals($response, $pool->getItems()[0]->getResponse()->getBody());
        $this->assertEmpty($pool->getErrors()->getErrors());
    }
    
    /**
     * @return void
     */
    public function testFailedRequestSend(): void
    {
        $this->expectException(PoolException::class);
        
        $pool = new Pool((new ClientFactory([]))->create());
        $item = (new Item())
            ->setClosure(function () {
                return new RejectedPromise(new Exception('request failed'));
            })
            ->setEntity(new FileEntity());

        $pool->add($item)->send();
    }
}
