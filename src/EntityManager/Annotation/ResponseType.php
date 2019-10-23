<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation;

use InvalidArgumentException;

/**
 * Class ResponseType
 *
 * @package SignNow\Rest\EntityManager\Annotation
 *
 * @Annotation
 */
class ResponseType extends Annotation
{
    public const DEFAULT_TYPE = 'self';
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['value'])) {
            $this->type = $options['value'];
        } else {
            throw new InvalidArgumentException('You must define response type');
        }
    }
    
    /**
     * @param mixed ...$args
     */
    public function initialize(...$args)
    {
        if ($this->type === self::DEFAULT_TYPE) {
            $this->type = $this->owner->getEntityClass();
        }
        
        if (!class_exists($this->type)) {
            throw new InvalidArgumentException('Response type ['.$this->type.'] does not exists');
        }
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
