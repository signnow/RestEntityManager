<?php
declare(strict_types = 1);

namespace SignNow\Rest\Factories;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;

/**
 * Class ClientFactory
 *
 * @package SignNow\Rest\Factories
 */
class ClientFactory
{
    /**
     * @var array
     */
    private $clientOptions;
    
    /**
     * @var array
     */
    private $middlewareList;
    
    /**
     * @var HandlerFactory
     */
    private $factory;
    
    /**
     * ClientFactory constructor.
     *
     * @param array          $clientOptions
     * @param HandlerFactory $factory
     * @param array          $middlewareList
     */
    public function __construct(array $clientOptions, array $middlewareList = [], HandlerFactory $factory = null)
    {
        $this->clientOptions = $clientOptions;
        $this->middlewareList = $middlewareList;
        $this->factory = $factory ?: new HandlerFactory();
    }
    
    /**
     * @return ClientInterface
     */
    public function create(): ClientInterface
    {
        $stack = HandlerStack::create();
        $stack->setHandler($this->factory->create());
    
        $defaultClientOptions = [
            'handler' => $stack,
        ];
        
        foreach ($this->middlewareList as $middleware) {
            $stack->push($middleware);
        }
        
        return new Client(array_merge($defaultClientOptions, $this->clientOptions));
    }
}
