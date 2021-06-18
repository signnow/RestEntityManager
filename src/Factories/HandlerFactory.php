<?php
declare(strict_types = 1);

namespace SignNow\Rest\Factories;

use GuzzleHttp\Handler\CurlFactory;
use GuzzleHttp\Handler\CurlMultiHandler;

/**
 * Class CurlMultiHandlerFactory
 *
 * @package SignNow\Rest\Factories
 */
class HandlerFactory
{
    /**
     * @var int
     */
    private $handlers;
    
    /**
     * @var int
     */
    private $selectTimeout;
    
    /**
     * CurlMultiHandlerFactory constructor.
     *
     * @param int $handlers
     * @param int $selectTimeout
     */
    public function __construct(int $handlers = 10, int $selectTimeout = 1)
    {
        $this->handlers = $handlers;
        $this->selectTimeout = $selectTimeout;
    }
    
    /**
     * @return CurlMultiHandler
     */
    public function create()
    {
        return new CurlMultiHandler([
            'handle_factory' => new CurlFactory($this->handlers),
            'select_timeout' => $this->selectTimeout
        ]);
    }
}
