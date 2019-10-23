<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation;

use SignNow\Rest\Entity\Entity;

/**
 * Class Annotation
 *
 * @package SignNow\Rest\EntityManager\Annotation
 */
abstract class Annotation
{
    /**
     * @var Entity
     */
    protected $owner;
    
    /**
     * @param Entity $entity
     *
     * @return Annotation
     */
    public function setOwner(Entity $entity): self
    {
        $this->owner = $entity;
        
        return $this;
    }
    
    /**
     * Initialize logic for annotation
     *
     * @param array $args
     */
    public function initialize(...$args)
    {
    
    }
}
