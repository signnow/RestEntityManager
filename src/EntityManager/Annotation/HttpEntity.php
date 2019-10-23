<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Class HttpEntity
 *
 * @package SignNow\Rest\EntityManager\Annotation
 *
 * @Annotation
 */
class HttpEntity extends Annotation
{
    /**
     * @var string
     */
    protected $uri;
    
    /**
     * @var string
     */
    protected $idProperty = 'id';
    
    /**
     * @var array
     */
    protected $uriParams = [];
    
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['value'])) {
            $options['uri'] = $options['value'];
            unset($options['value']);
        }
        
        foreach ($options as $key => $value) {
            if (!property_exists($this, $key)) {
                throw new InvalidArgumentException(sprintf('Property "%s" does not exist', $key));
            }
            
            $this->$key = $value;
        }
    }
    
    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return trim($this->replaceUriParams($this->uri, $this->uriParams), '/');
    }
    
    /**
     * @param mixed ...$args
     */
    public function initialize(...$args): void
    {
        $this->uriParams = isset($args[0]) ? $args[0] : [];
        $id = $this->getIdValue();
        
        if (!empty($id)) {
            $this->uriParams[$this->idProperty] = $id;
        }
    }
    
    /**
     * Replacing uri params {param1}/{param2} on param1, param2
     *
     * @param string $uri
     * @param array  $params
     *
     * @return string
     */
    private function replaceUriParams($uri, array $params): string
    {
        $placeholders = array_map(function ($element) {
            return '{'.$element.'}';
        }, array_keys($params));
        
        return str_replace($placeholders, $params, $uri);
    }
    
    /**
     * @return string
     */
    private function getIdValue(): string
    {
        if (empty($this->idProperty)) {
            return '';
        }
        
        $entityIdGetter = 'get'.ucfirst($this->idProperty);
        if (!method_exists($this->owner, $entityIdGetter)) {
            throw new BadMethodCallException('Id getter does not exist');
        }
        
        return (string)$this->owner->$entityIdGetter();
    }
}
