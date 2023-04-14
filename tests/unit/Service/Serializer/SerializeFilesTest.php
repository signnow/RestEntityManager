<?php
declare(strict_types = 1);

namespace Tests\Unit\Service\Serializer;

use GuzzleHttp\ClientInterface;
use SignNow\Serializer\Serializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use ReflectionException;
use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager;
use SignNow\Rest\EntityManager\AnnotationResolver;
use SignNow\Rest\EntityManager\Exception\EntityManagerException;
use SignNow\Rest\Factories\AnnotationResolverFactory;
use SignNow\Rest\Factories\SerializerBuilderFactory;
use SignNow\Rest\Service\Request\Pool;
use SignNow\Rest\Service\Request\Pool\Item;
use SignNow\Rest\Service\Serializer\Type\File;
use SignNow\Rest\Service\Serializer\Type\FileLink;
use SplFileInfo;
use Tests\Fixtures\Entity\TemplateEntity;
use Tests\Fixtures\Entity\Upload\FileLinkUpload;
use Tests\Fixtures\Entity\Upload\FileUpload;
use Tests\Fixtures\Entity\Upload\SplFileInfoUpload;
use Tests\Fixtures\Entity\StatusResponse;

/**
 * Class SerializeFilesTest
 */
class SerializeFilesTest extends TestCase
{
    /**
     * @var Serializer
     */
    private $serializer;
    
    /**
     * @var AnnotationResolver
     */
    private $annotationResolver;
    
    /**
     * @var ClientInterface
     */
    private $client;
    
    /**
     * @throws ReflectionException
     */
    public function setUp(): void
    {
        $this->client = $this->getMockForAbstractClass(ClientInterface::class);
        $this->annotationResolver = (new AnnotationResolverFactory())->create();
        $this->serializer = (new SerializerBuilderFactory())->create()->build();
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function testSplFileInfoSerialize(): void
    {
        $requestEntity = new SplFileInfoUpload(new SplFileInfo(""), [['type' => 'text'], ['type' => 'signature']]);
        
        $entityManager = new EntityManager(
            $this->client,
            $this->serializer,
            $this->annotationResolver,
            $this->createPoolMock($requestEntity)
        );
        
        $response = $entityManager->create($requestEntity);
        
        $this->assertInstanceOf(StatusResponse::class, $response);
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function testFileSerialize(): void
    {
        $requestEntity = new FileUpload(new File("content", "filename"));
        
        $entityManager = new EntityManager(
            $this->client,
            $this->serializer,
            $this->annotationResolver,
            $this->createPoolMock($requestEntity)
        );
        
        $response = $entityManager->create($requestEntity);
        
        $this->assertInstanceOf(StatusResponse::class, $response);
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function testFileLinkSerialize(): void
    {
        $requestEntity = new FileLinkUpload(new FileLink("https://google.com", "filename"));
        
        $entityManager = new EntityManager(
            $this->client,
            $this->serializer,
            $this->annotationResolver,
            $this->createPoolMock($requestEntity)
        );
        
        $response = $entityManager->create($requestEntity);
        
        $this->assertInstanceOf(StatusResponse::class, $response);
    }
    
    /**
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function testFormParamsEntitySerialize()
    {
        $requestEntity = new TemplateEntity("543465235423");
    
        $entityManager = new EntityManager(
            $this->client,
            $this->serializer,
            $this->annotationResolver,
            $this->createPoolMock($requestEntity)
        );
    
        $response = $entityManager->create($requestEntity);
    
        $this->assertInstanceOf(StatusResponse::class, $response);
    }
    
    /**
     * @param Entity $requestEntity
     *
     * @return Pool
     *
     * @throws ReflectionException
     */
    private function createPoolMock(Entity $requestEntity): Pool
    {
        /** @var StreamInterface|MockObject $responseStream */
        $responseStream = $this->getMockForAbstractClass(StreamInterface::class);
        $responseStream
            ->method('getContents')
            ->willReturn('{"status":"success"}');
    
        /** @var ResponseInterface|MockObject $response */
        $response = $this->getMockForAbstractClass(ResponseInterface::class);
        $response
            ->method('getBody')
            ->willReturn($responseStream);
    
        $item = $this->getMockBuilder(Item::class)
            ->setMethods(['getResponse', 'getEntity'])
            ->getMock();
        $item
            ->method('getResponse')
            ->willReturn($response);
        $item
            ->method('getEntity')
            ->willReturn($requestEntity);
    
        $pool = $this->getMockBuilder(Pool::class)
            ->setConstructorArgs([$this->client])
            ->setMethods(['send', 'getItems'])
            ->getMock();
        $pool
            ->method('send')
            ->willReturn($pool);
        $pool
            ->method('getItems')
            ->willReturn([$item]);
        
        return $pool;
    }
}
