<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Request\Pool;

use Closure;
use Psr\Http\Message\ResponseInterface;
use SignNow\Rest\Entity\Entity;
use SignNow\Rest\Entity\Error;

/**
 * Class Item
 *
 * @package SignNow\Rest\Services\Request\Pool
 */
class Item
{
    /**
     * @var Closure
     */
    protected $closure;
    
    /**
     * @var Entity
     */
    protected $entity;
    
    /**
     * @var ResponseInterface
     */
    protected $response;
    
    /**
     * @var Error
     */
    protected $error;
    
    /**
     * @return Closure
     */
    public function getClosure(): Closure
    {
        return $this->closure;
    }
    
    /**
     * @param Closure $closure
     *
     * @return Item
     */
    public function setClosure(Closure $closure): self
    {
        $this->closure = $closure;
        
        return $this;
    }
    
    /**
     * @return Entity
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }
    
    /**
     * @param Entity $entity
     *
     * @return Item
     */
    public function setEntity(Entity $entity): self
    {
        $this->entity = $entity;
        
        return $this;
    }
    
    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
    
    /**
     * @param ResponseInterface $response
     *
     * @return Item
     */
    public function setResponse(ResponseInterface $response): self
    {
        $this->response = $response;
        
        return $this;
    }
    
    /**
     * @return Error|null
     */
    public function getError(): ?Error
    {
        return $this->error;
    }
    
    /**
     * @param Error $error
     *
     * @return Item
     */
    public function setError(?Error $error = null): self
    {
        $this->error = $error;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return !empty($this->error);
    }
}
