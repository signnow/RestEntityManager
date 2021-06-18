<?php
declare(strict_types = 1);

namespace Tests\Fixtures\Entity;

use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;
use SignNow\Rest\EntityManager\Annotation\ResponseType;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class FileEntity
 *
 * @HttpEntity("file/{id}")
 * @ResponseType("SignNow\Rest\Entity\Binary")
 *
 * @package Tests\Fixtures\Entity
 */
class FileEntity extends Entity
{
    /**
     * @var string|null
     * @Serializer\Type("string")
     */
    private $id;
    
    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
