<?php
declare(strict_types = 1);

namespace SignNow\Rest\Entity;

use Psr\Http\Message\ResponseInterface;

/**
 * Class Error
 *
 * @package SignNow\Rest\Entity
 */
class Error
{
    /**
     * @var ResponseInterface
     */
    private $response;
    
    /**
     * @var string
     */
    private $message;
    
    /**
     * @return ResponseInterface|null
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
    
    /**
     * @param ResponseInterface|null $response
     */
    public function setResponse(?ResponseInterface $response = null)
    {
        $this->response = $response;
    }
    
    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
    
    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }
}
