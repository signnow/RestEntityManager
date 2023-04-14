<?php
declare(strict_types = 1);

namespace Tests\Fixtures\Entity;

use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;
use SignNow\Rest\EntityManager\Annotation\ResponseType;
use SignNow\Serializer\Annotation as Serializer;

/**
 * Class TemplateEntity
 *
 * @HttpEntity("template", idProperty="")
 * @GuzzleRequestBody("form_params")
 * @ResponseType("Tests\Fixtures\Entity\StatusResponse")
 *
 * @package Tests\Fixtures\Entity
 */
class TemplateEntity extends Entity
{
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $id;
    
    /**
     * TemplateEntity constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }
}
