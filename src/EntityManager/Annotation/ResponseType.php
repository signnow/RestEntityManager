<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation;

use InvalidArgumentException;
use SignNow\Rest\Entity\Binary;

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
        } else if (preg_match("#(array<)?(?<type>[a-zA-Z0-9_\\\]+)>?#i", $this->type, $matches)) {
            $this->type = $matches['type'];
        }
        
        $this->type = trim($this->type, '\\');
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
    
    /**
     * @return bool
     */
    public function isBinary(): bool
    {
        return $this->type == Binary::class;
    }
}
