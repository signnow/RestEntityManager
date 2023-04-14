<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation;

use InvalidArgumentException;
use SignNow\Serializer\Type\Parser;
use SignNow\Rest\Entity\Binary;
use Throwable;

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
        
        try {
            (new Parser())->parse($this->type);
        } catch (Throwable $e) {
            throw new InvalidArgumentException('Invalid response type format: ' . $e->getMessage());
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
