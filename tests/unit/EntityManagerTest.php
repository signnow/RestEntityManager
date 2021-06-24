<?php
declare(strict_types = 1);

namespace Tests\Unit;

use Doctrine\Common\Annotations\AnnotationReader;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use ReflectionException;
use SignNow\Rest\Entity\Binary;
use SignNow\Rest\Entity\Collection\Errors;
use SignNow\Rest\Entity\Error;
use SignNow\Rest\EntityManager;
use SignNow\Rest\EntityManager\AnnotationFactory;
use SignNow\Rest\EntityManager\AnnotationResolver;
use SignNow\Rest\EntityManager\Exception\EntityManagerException;
use SignNow\Rest\EntityManager\Exception\PoolException;
use SignNow\Rest\Http\Request;
use SignNow\Rest\Service\Request\Pool;
use SignNow\Rest\Service\Request\Pool\Item;
use Tests\Fixtures\Entity\FieldEntity;
use Tests\Fixtures\Entity\FileEntity;
use Tests\Fixtures\Entity\SimpleEntity;

/**
 * Class EntityManagerTest
 *
 * @package Tests\Unit
 *
 * @coversDefaultClass \SignNow\Rest\EntityManager
 */
class EntityManagerTest extends TestCase
{
    /**
     * @var ClientInterface
     */
    private $client;
    
    /**
     * @var Serializer
     */
    private $serializer;
    
    /**
     * @var AnnotationResolver
     */
    private $resolver;
    
    /**
     * @return void
     *
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        $this->client = $this->getMockForAbstractClass(ClientInterface::class);
        $this->serializer = SerializerBuilder::create()->build();
        $this->resolver = new AnnotationResolver(new AnnotationReader(), new AnnotationFactory());
    }
    
    /**
     * @param string $entityClass
     * @param string $response
     * @param string $expectedId
     * @param string $expectedType
     *
     * @throws EntityManagerException
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws ReflectionException
     *
     * @dataProvider getDataProvider
     * @covers ::get
     * @covers \SignNow\Rest\EntityManager\Annotation\ResponseType
     * @covers \SignNow\Rest\EntityManager\Annotation\HttpEntity
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     * @covers \SignNow\Rest\EntityManager
     * @covers \SignNow\Rest\Service\Request\Pool\Item
     * @covers \SignNow\Rest\Service\Request\Pool
     * @covers \SignNow\Rest\EntityManager\Annotation\Annotation
     * @covers \SignNow\Rest\EntityManager\AnnotationFactory
     * @covers \SignNow\Rest\Entity\Entity
     * @covers \SignNow\Rest\Entity\Collection\Errors
     */
    public function testGet(string $entityClass, string $response, string $expectedId, string $expectedType): void
    {
        $pool = $this->createPool($response);
        
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver, $pool);
        
        $entity = $entityManager->get($entityClass, ['id' => $expectedId]);
        
        $this->assertEquals($expectedId, $entity->getId());
        $this->assertEquals($expectedType, $entity->getType());
    }
    
    /**
     * @param FieldEntity $entity
     * @param string      $response
     *
     * @throws EntityManagerException
     * @throws ReflectionException
     *
     * @dataProvider createDataProvider
     * @covers ::create
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormatterFactory
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\JsonFormatter
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Formatter
     * @covers \SignNow\Rest\Entity\Entity
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
     * @covers \SignNow\Rest\EntityManager\Annotation\Annotation
     * @covers \SignNow\Rest\Service\Request\Pool\Item
     * @covers \SignNow\Rest\Service\Request\Pool
     * @covers \SignNow\Rest\EntityManager\Annotation\ResponseType
     * @covers \SignNow\Rest\EntityManager\Annotation\HttpEntity
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     * @covers \SignNow\Rest\EntityManager\AnnotationFactory
     * @covers \SignNow\Rest\EntityManager
     * @covers \SignNow\Rest\Entity\Collection\Errors
     */
    public function testCreate(FieldEntity $entity, string $response): void
    {
        $pool = $this->createPool($response);
        
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver, $pool);

        $createdEntity = $entityManager->create($entity);

        $this->assertNotSame($entity, $createdEntity);
        $this->assertEquals($entity->getId(), $createdEntity->getId());
        $this->assertEquals($entity->getType(), $createdEntity->getType());
    }
    
    /**
     * @param FieldEntity $entity
     * @param string      $expectedType
     * @param string      $response
     *
     * @throws EntityManagerException
     * @throws ReflectionException
     *
     * @dataProvider updateDataProvider
     * @covers ::update
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormatterFactory
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\JsonFormatter
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Formatter
     * @covers \SignNow\Rest\Entity\Entity
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
     * @covers \SignNow\Rest\EntityManager\Annotation\Annotation
     * @covers \SignNow\Rest\Service\Request\Pool\Item
     * @covers \SignNow\Rest\Service\Request\Pool
     * @covers \SignNow\Rest\EntityManager\Annotation\ResponseType
     * @covers \SignNow\Rest\EntityManager\Annotation\HttpEntity
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     * @covers \SignNow\Rest\EntityManager\AnnotationFactory
     * @covers \SignNow\Rest\EntityManager
     * @covers \SignNow\Rest\Entity\Collection\Errors
     */
    public function testUpdate(FieldEntity $entity, string $expectedType, string $response): void
    {
        $pool = $this->createPool($response);
        
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver, $pool);
    
        $entity->setType($expectedType);
        
        $updatedEntity = $entityManager->update($entity);
        
        $this->assertNotSame($entity, $updatedEntity);
        $this->assertEquals($expectedType, $updatedEntity->getType());
    }

    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     *
     * @covers ::delete
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormatterFactory
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\JsonFormatter
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Formatter
     * @covers \SignNow\Rest\Entity\Entity
     * @covers \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
     * @covers \SignNow\Rest\EntityManager\Annotation\Annotation
     * @covers \SignNow\Rest\Service\Request\Pool\Item
     * @covers \SignNow\Rest\Service\Request\Pool
     * @covers \SignNow\Rest\EntityManager\Annotation\ResponseType
     * @covers \SignNow\Rest\EntityManager\Annotation\HttpEntity
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     * @covers \SignNow\Rest\EntityManager\AnnotationFactory
     * @covers \SignNow\Rest\EntityManager
     * @covers \SignNow\Rest\Entity\Collection\Errors
     */
    public function testDelete(): void
    {
        $pool = $this->createPool('');

        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver, $pool);

        $entity = (new FieldEntity())
            ->setId('85ed071eb4bd47a4769be9af0303e91d7303c62d');

        $this->assertNotEmpty($entityManager->delete($entity));
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     *
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     * @covers \SignNow\Rest\EntityManager\Annotation\HttpEntity
     * @covers \SignNow\Rest\EntityManager\Annotation\ResponseType
     * @covers \SignNow\Rest\Service\Request\Pool
     * @covers \SignNow\Rest\Service\Request\Pool\Item
     * @covers \SignNow\Rest\EntityManager
     * @covers \SignNow\Rest\Entity\Collection\Errors
     * @covers \SignNow\Rest\Entity\Entity
     * @covers \SignNow\Rest\Entity\Binary
     * @covers \SignNow\Rest\EntityManager\Annotation\Annotation
     */
    public function testBinaryFileResponseRetrieving(): void
    {
        $binaryContent = base64_encode('some content');
        
        /** @var StreamInterface|MockObject $responseStream */
        $responseStream = $this->getMockForAbstractClass(StreamInterface::class);
        $responseStream
            ->method('getContents')
            ->willReturn($binaryContent);
    
        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockForAbstractClass(ResponseInterface::class);
        $response
            ->method('getBody')
            ->willReturn($responseStream);
    
        /** @var Item|MockObject $item */
        $item = $this->getMockBuilder(Item::class)
            ->setMethods(['getResponse', 'getEntity'])
            ->getMock();
        $item
            ->method('getResponse')
            ->willReturn($response);
        $item
            ->method('getEntity')
            ->willReturn(new FileEntity());
    
        /** @var Pool|MockObject $pool */
        $pool = $this->getMockBuilder(Pool::class)
            ->setConstructorArgs([$this->client])
            ->setMethods(['getItems', 'send'])
            ->getMock();
    
        $pool
            ->method('getItems')
            ->willReturn([$item]);
    
        $pool
            ->method('send')
            ->willReturn($pool);
        
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver, $pool);
        
        /** @var Binary $binaryResponse */
        $binaryResponse = $entityManager->get(FileEntity::class, ['id' => 'id']);
        
        $this->assertInstanceOf(Binary::class, $binaryResponse);
        $this->assertEquals($binaryContent, $binaryResponse->getContent());
    }
    
    /**
     * @throws ReflectionException
     *
     * @covers ::setClient
     * @covers \SignNow\Rest\EntityManager
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     * @covers \SignNow\Rest\Service\Request\Pool
     */
    public function testSetClient(): void
    {
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver);
        
        $newClient = $this->getMockForAbstractClass(ClientInterface::class);
        
        $entityManager->setClient($newClient);
        
        $this->assertAttributeEquals($newClient, 'client', $entityManager);
    }
    
    /**
     * @covers ::setUpdateHttpMethod
     * @covers ::__construct
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     * @covers \SignNow\Rest\Service\Request\Pool
     */
    public function testSetHttpMethod(): void
    {
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver);
        $entityManager->setUpdateHttpMethod(Request::METHOD_PUT);
        
        $this->assertAttributeEquals(Request::METHOD_PUT, 'updateHttpMethod', $entityManager);
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     *
     * @covers ::get
     * @covers ::__construct
     * @covers ::createEntity
     * @covers \SignNow\Rest\EntityManager\AnnotationResolver
     * @covers \SignNow\Rest\Service\Request\Pool
     */
    public function testExceptionOnInvalidEntity(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver);
        $entityManager->get(SimpleEntity::class);
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function testRequestPool(): void
    {
        $entityManager = new EntityManager(
            $this->client,
            $this->serializer,
            $this->resolver,
            $this->createPool('{"id": "030b82bb8671c23d5b8bc7808759aa605f48b8e0", "type": "signature"}')
        );
        $entityManager->openRequestPool();
        $entityManager->get(FieldEntity::class, ['id' => '030b82bb8671c23d5b8bc7808759aa605f48b8e0']);
        $responses = $entityManager->sendRequestPool();
        
        $this->assertNotEmpty($responses);
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function testAnErrorOnSendingRequestPoolWithoutRequest(): void
    {
        $this->expectException(LogicException::class);
        
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver, new Pool($this->client));
        $entityManager->openRequestPool();
        $entityManager->sendRequestPool();
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function testAnErrorFromRequest(): void
    {
        $this->expectException(EntityManagerException::class);
    
        $pool = $this->getMockBuilder(Pool::class)
            ->setConstructorArgs([$this->client])
            ->setMethods(['send', 'getErrors'])
            ->getMock();
        
        $error = new Error();
        $error->setMessage('some error');
        
        $errorCollection = new Errors();
        $errorCollection->push(0, $error);
        
        $pool
            ->method('getErrors')
            ->willReturn($errorCollection);
        $pool
            ->method('send')
            ->willThrowException(new PoolException());
        
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver, $pool);
        $entityManager->openRequestPool();
        $entityManager->get(FileEntity::class);
        $entityManager->sendRequestPool();
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function testRetrievingErrorsFormPool(): void
    {
        $pool = $this->getMockBuilder(Pool::class)
            ->setConstructorArgs([$this->client])
            ->setMethods(['send', 'getErrors'])
            ->getMock();
    
        $response = $this->getMockForAbstractClass(ResponseInterface::class);
        $error = new Error();
        $error->setMessage('some error');
        $error->setResponse($response);
    
        $errorCollection = new Errors();
        $errorCollection->push(0, $error);
    
        $pool
            ->method('getErrors')
            ->willReturn($errorCollection);
        $pool
            ->method('send')
            ->willThrowException(new PoolException());
    
        $entityManager = new EntityManager($this->client, $this->serializer, $this->resolver, $pool);
        $entityManager->openRequestPool();
        $entityManager->get(FileEntity::class);
        try {
            $entityManager->sendRequestPool();
        } catch (EntityManagerException $e) {
            // skip exception to retrieve all errors
        }
        
        $this->assertNotEmpty($entityManager->getErrors());
        $this->assertSame($response, $entityManager->getErrors()->getError(0)->getResponse());
    }
    
    /**
     * @return array
     */
    public function createDataProvider(): array
    {
        $entity = (new FieldEntity())
            ->setId('030b82bb8671c23d5b8bc7808759aa605f48b8e0')
            ->setType('signature');
        
        return [
            [
                $entity,
                '{"id": "030b82bb8671c23d5b8bc7808759aa605f48b8e0", "type": "signature"}',
            ]
        ];
    }
    
    /**
     * @return array
     */
    public function updateDataProvider(): array
    {
        $entity = (new FieldEntity())
            ->setId('030b82bb8671c23d5b8bc7808759aa605f48b8e0')
            ->setType('signature');
            
        return [
            [
                $entity,
                'text',
                '{"id": "030b82bb8671c23d5b8bc7808759aa605f48b8e0", "type": "text"}'
            ]
        ];
    }
    
    /**
     * @return array
     */
    public function getDataProvider(): array
    {
        return [
            [
                FieldEntity::class,
                '{"id": "030b82bb8671c23d5b8bc7808759aa605f48b8e0", "type": "signature"}',
                '030b82bb8671c23d5b8bc7808759aa605f48b8e0',
                'signature'
            ],
            [
                FieldEntity::class,
                '{"id": "85ed071eb4bd47a4769be9af0303e91d7303c62d", "type": "text"}',
                '85ed071eb4bd47a4769be9af0303e91d7303c62d',
                'text'
            ],
        ];
    }
    
    /**
     * @param string $responseContent
     *
     * @return MockObject|Pool
     *
     * @throws ReflectionException
     */
    private function createPool(string $responseContent)
    {
        /** @var StreamInterface|MockObject $responseStream */
        $responseStream = $this->getMockForAbstractClass(StreamInterface::class);
        $responseStream
            ->method('getContents')
            ->willReturn($responseContent);
    
        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockForAbstractClass(ResponseInterface::class);
        $response
            ->method('getBody')
            ->willReturn($responseStream);
    
        /** @var Item|MockObject $item */
        $item = $this->getMockBuilder(Item::class)
            ->setMethods(['getResponse', 'getEntity'])
            ->getMock();
        $item
            ->method('getResponse')
            ->willReturn($response);
        $item
            ->method('getEntity')
            ->willReturn(new FieldEntity());
    
        /** @var Pool|MockObject $pool */
        $pool = $this->getMockBuilder(Pool::class)
            ->setConstructorArgs([$this->client])
            ->setMethods(['getItems', 'send'])
            ->getMock();
    
        $pool
            ->method('getItems')
            ->willReturn([$item]);
    
        $pool
            ->method('send')
            ->willReturn($pool);
        
        return $pool;
    }
}
