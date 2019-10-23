<?php
declare(strict_types = 1);

namespace Tests\Fixtures\Entity;

use JMS\Serializer\Annotation as Serializer;
use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;

/**
 * Class FieldEntity
 *
 * @package Tests\Fixtures
 *
 * @HttpEntity("/field/{id}")
 */
class FieldEntity extends Entity
{
    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $id;
    
    /**
     * @var string
     * @Serializer\Type("string")
     */
    private $type;
    
    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }
    
    /**
     * @param string $id
     *
     * @return FieldEntity
     */
    public function setId(string $id): self
    {
        $this->id = $id;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * @param string $type
     *
     * @return FieldEntity
     */
    public function setType(string $type): self
    {
        $this->type = $type;
        
        return $this;
    }
}
