<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Request;

use Exception;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use LogicException;
use SignNow\Rest\Entity\Collection\Errors;
use SignNow\Rest\EntityManager\Exception\PoolException;
use SignNow\Rest\Service\Request\Handler\DefaultErrorHandler;
use SignNow\Rest\Service\Request\Handler\ErrorHandlerInterface;
use SignNow\Rest\Service\Request\Pool\Item;

/**
 * Class Pool
 *
 * @package SignNow\Rest\Services\Request
 */
class Pool implements PoolInterface
{
    /**
     * @var ClientInterface
     */
    protected $client;
    
    /**
     * @var ErrorHandlerInterface
     */
    private $errorHandler;
    
    /**
     * @var Item[]
     */
    protected $items = [];
    
    /**
     * @var Errors
     */
    protected $errors;
    
    /**
     * Pool constructor.
     *
     * @param ClientInterface $client
     * @param ErrorHandlerInterface|null $errorHandler
     */
    public function __construct(ClientInterface $client, ?ErrorHandlerInterface $errorHandler = null)
    {
        $this->client = $client;
        $this->errorHandler = $errorHandler ?: new DefaultErrorHandler();
        $this->errors = new Errors();
    }
    
    /**
     * @param int|null $concurrencyLimit
     *
     * @return PoolInterface
     * @throws PoolException
     */
    public function send(int $concurrencyLimit = null): PoolInterface
    {
        if (empty($this->items)) {
            throw new LogicException('Please add items to request pool first');
        }
        
        $requests = array_map(function (Item $item) {
            return $item->getClosure();
        }, $this->items);
        
        $options = [
            'fulfilled' => $this->onFulfilled(),
            'rejected' => $this->onRejected(),
        ];
        
        if ($concurrencyLimit) {
            $options['concurrency'] = $concurrencyLimit;
        }
        
        $this->createPool($requests, $options)->promise()->wait();
        
        if ($this->errors->hasErrors()) {
            throw new PoolException();
        }
        
        return $this;
    }
    
    /**
     * @param array $requests
     * @param array $options
     *
     * @return \GuzzleHttp\Pool
     */
    protected function createPool(array $requests, array $options)
    {
        return new \GuzzleHttp\Pool($this->client, $requests, $options);
    }
    
    /**
     * @inheritdoc
     */
    public function add(Item $item): PoolInterface
    {
        $this->items[] = $item;
        
        return $this;
    }
    
    /**
     * @inheritdoc
     */
    public function getItems(): array
    {
        return $this->items;
    }
    
    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return count($this->items) === 0;
    }
    
    /**
     * Clear pool
     */
    public function clear(): void
    {
        $this->errors->clear();
        $this->items = [];
    }
    
    /**
     * @return Errors
     */
    public function getErrors(): Errors
    {
        return $this->errors;
    }
    
    /**
     * @return callable
     */
    protected function onFulfilled(): callable
    {
        return function (Response $response, $index) {
            $this->items[$index]->setResponse($response);
        };
    }
    
    /**
     * @return callable
     */
    protected function onRejected(): callable
    {
        return function (Exception $reason, $index, $promise) {
            $error = $this->errorHandler->handle($reason);
            
            $this->errors->push((int)$index, $error);
            $this->items[$index]->setError($error);
        };
    }
}
