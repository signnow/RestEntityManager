<?php
declare(strict_types = 1);

namespace Tests\Unit;

use Doctrine\Common\Annotations\AnnotationReader;
use GuzzleHttp\ClientInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use SignNow\Rest\EntityManager;
use SignNow\Rest\EntityManager\AnnotationFactory;
use SignNow\Rest\EntityManager\AnnotationResolver;
use SignNow\Rest\Service\Request\Pool;
use SignNow\Rest\Service\Request\Pool\Item;
use Tests\Fixtures\Entity\FieldEntity;

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
     * @throws \ReflectionException
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
     * @throws EntityManager\Exception\EntityManagerException
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
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
     * @throws EntityManager\Exception\EntityManagerException
     * @throws \ReflectionException
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
     * @throws EntityManager\Exception\EntityManagerException
     * @throws \ReflectionException
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
     * @throws EntityManager\Exception\EntityManagerException
     * @throws \ReflectionException
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
     * @throws \ReflectionException
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
