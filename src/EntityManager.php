<?php
declare(strict_types = 1);

namespace SignNow\Rest;

use Closure;
use GuzzleHttp\ClientInterface;
use InvalidArgumentException;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use LogicException;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use ReflectionException;
use SignNow\Rest\Entity\Binary;
use SignNow\Rest\Entity\Collection\Errors;
use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager\Annotation;
use SignNow\Rest\EntityManager\AnnotationResolver;
use SignNow\Rest\EntityManager\Exception\EntityManagerException;
use SignNow\Rest\EntityManager\Exception\PoolException;
use SignNow\Rest\Http\Request;
use SignNow\Rest\Service\Request\Pool;
use SignNow\Rest\Service\Request\Pool\Item;
use SignNow\Rest\Service\Request\PoolInterface;

/**
 * Class EntityManager
 *
 * @package SignNow\Rest
 */
class EntityManager
{
    /**
     * @var ClientInterface
     */
    protected $client;
    
    /**
     * @var Serializer
     */
    protected $serializer;
    
    /**
     * @var AnnotationResolver
     */
    protected $resolver;
    
    /**
     * @var Errors
     */
    protected $errors;
    
    /**
     * @var string
     */
    protected $updateHttpMethod = Request::METHOD_PATCH;
    
    /**
     * @var PoolInterface
     */
    protected $requestPool;
    
    /**
     * @var bool
     */
    protected $isRequestPoolOpened = false;
    
    /**
     * EntityManager constructor.
     *
     * @param ClientInterface     $client
     * @param Serializer          $serializer
     * @param AnnotationResolver  $resolver
     * @param PoolInterface       $requestPool
     */
    public function __construct(
        ClientInterface $client,
        Serializer $serializer,
        AnnotationResolver $resolver,
        PoolInterface $requestPool = null
    )
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->resolver = $resolver;
        $this->requestPool = $requestPool ?: new Pool($client);
    }
    
    /**
     * @param ClientInterface $client
     *
     * @return EntityManager
     */
    public function setClient(ClientInterface $client): self
    {
        $this->client = $client;
        
        return $this;
    }
    
    /**
     * @param string|Entity $entity
     * @param array         $uriParams
     * @param array         $queryParams
     * @param array         $headers
     *
     * @return EntityManager|object
     *
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function get($entity, array $uriParams = [], array $queryParams = [], array $headers = [])
    {
        $entity = is_object($entity) ? $entity : $this->createEntity($entity);
        $httpEntity = $this->resolver->getHttpEntity($entity, $uriParams);
        $options = [
            'query' => $queryParams,
            'headers' => $headers,
        ];
        
        return $this
            ->addPoolItem(
                $this->getRequestClosure(Request::METHOD_GET, $httpEntity, $options),
                $entity
            )
            ->send();
    }
    
    /**
     * @param Entity $entity
     * @param array  $uriParams
     * @param array  $queryParams
     * @param array  $headers
     *
     * @return object
     *
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function create(Entity $entity, array $uriParams = [], array $queryParams = [], array $headers = [])
    {
        $options = [
            'query' => $queryParams,
            'headers' => $headers,
        ];
        $options = array_merge($options, $this->getRequestOptions($entity));
        $httpEntity = $this->resolver->getHttpEntity($entity, $uriParams);
    
        return $this
            ->addPoolItem(
                $this->getRequestClosure(Request::METHOD_POST, $httpEntity, $options),
                $entity
            )
            ->send();
    }
    
    /**
     * @param Entity $entity
     * @param array  $uriParams
     * @param array  $queryParams
     * @param array  $headers
     *
     * @return object
     *
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function update(Entity $entity, $uriParams = [], $queryParams = [], array $headers = [])
    {
        $options = [
            'query' => $queryParams,
            'headers' => $headers,
        ];
        $options = array_merge($options, $this->getRequestOptions($entity));
        $httpEntity = $this->resolver->getHttpEntity($entity, $uriParams);
    
        return $this
            ->addPoolItem(
                $this->getRequestClosure($this->updateHttpMethod, $httpEntity, $options),
                $entity
            )
            ->send();
    }
    
    /**
     * @param Entity $entity
     * @param array  $uriParams
     * @param array  $queryParams
     * @param array  $headers
     *
     * @return object
     *
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function delete(Entity $entity, $uriParams = [], $queryParams = [], array $headers = [])
    {
        $options = [
            'query' => $queryParams,
            'headers' => $headers,
        ];
        $httpEntity = $this->resolver->getHttpEntity($entity, $uriParams);
        
        return $this
            ->addPoolItem(
                $this->getRequestClosure(Request::METHOD_DELETE, $httpEntity, $options),
                $entity
            )
            ->send();
    }
    
    /**
     * @param string $updateHttpMethod
     *
     * @return $this
     */
    public function setUpdateHttpMethod(string $updateHttpMethod)
    {
        $this->updateHttpMethod = $updateHttpMethod;
        
        return $this;
    }
    
    /**
     * @param string                $method
     * @param Annotation\HttpEntity $httpEntity
     * @param array                 $options
     *
     * @return Closure
     */
    protected function getRequestClosure(string $method, Annotation\HttpEntity $httpEntity, array $options): Closure
    {
        return function () use ($method, $httpEntity, $options) {
            return $this->client->requestAsync(
                $method,
                $httpEntity->getEndpoint(),
                $options
            );
        };
    }
    
    /**
     * @param Entity $entity
     *
     * @return array
     * @throws ReflectionException
     */
    protected function getRequestOptions(Entity $entity)
    {
        $options = [];
        $requestBody = $this->resolver->getRequestBody($entity);
        $content = $this->serializer->toArray($entity, SerializationContext::create()->setGroups(['Default']));
        
        if (!empty($content)) {
            $options[$requestBody->getGuzzleRequestOption()] = $requestBody->prepare($content);
        }
        
        return $options;
    }
    
    /**
     * Open request pool
     */
    public function openRequestPool()
    {
        if (!$this->requestPool) {
            throw new LogicException('Pool service doesn\'t exists');
        }
        
        $this->isRequestPoolOpened = true;
    }
    
    /**
     * @return Entity|void
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    protected function send()
    {
        if (false === $this->isRequestPoolOpened) {
            $pool = $this->sendRequestPool();
            
            return array_shift($pool);
        }
    }
    
    /**
     * @param integer $concurrencyLimit maximum number of parallel calls
     *
     * @return array
     * @throws EntityManagerException
     * @throws ReflectionException
     */
    public function sendRequestPool(int $concurrencyLimit = 5)
    {
        if ($this->requestPool->isEmpty()) {
            throw new LogicException('Request pool service is empty');
        }
        
        $results = [];
        
        try {
            $this->requestPool->send($concurrencyLimit);
            
            /**
             * @var int  $index
             * @var Item $item
             */
            foreach ($this->requestPool->getItems() as $index => $item) {
                $results[$index] = $this->deserialize($item->getResponse(), $item->getEntity());
            }
            
        } catch (PoolException $e) {
            $this->errors = $this->requestPool->getErrors();
            
            throw new EntityManagerException("Request failed!\nReason:\n".$this->errors->getFullMessage());
        } finally {
            $this->isRequestPoolOpened = false;
            $this->requestPool->clear();
        }
        
        return $results;
    }
    
    /**
     * @return Errors
     */
    public function getErrors(): Errors
    {
        return $this->errors;
    }
    
    /**
     * @param Closure $requestClosure
     * @param Entity  $entity
     *
     * @return $this
     */
    protected function addPoolItem(Closure $requestClosure, Entity $entity)
    {
        $poolItem = new Item();
        $poolItem
            ->setClosure($requestClosure)
            ->setEntity($entity);
        $this->requestPool->add($poolItem);
        
        return $this;
    }
    
    /**
     * @param ResponseInterface $response
     * @param Entity            $entity
     *
     * @return object
     * @throws ReflectionException
     */
    protected function deserialize(ResponseInterface $response, Entity $entity)
    {
        $responseType = $this->resolver->getResponseType($entity);
        
        if (trim($responseType->getType(), "\\") == Binary::class) {
            return (new Binary())->setContent($response->getBody()->getContents());
        }
        
        return $this->serializer->deserialize(
            $response->getBody()->getContents() ?: '[]',
            $responseType->getType(),
            'json'
        );
    }
    
    /**
     * @param string $entityClass
     *
     * @return object
     * @throws ReflectionException
     */
    protected function createEntity(string $entityClass)
    {
        $reflection = new ReflectionClass($entityClass);
        
        if (!$reflection->isSubclassOf(Entity::class)) {
            throw new InvalidArgumentException('Entity must be a subclass of '.Entity::class);
        }
        
        return $reflection->newInstanceWithoutConstructor();
    }
}
