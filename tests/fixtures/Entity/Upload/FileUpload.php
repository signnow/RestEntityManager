<?php
declare(strict_types = 1);

namespace Tests\Fixtures\Entity\Upload;

use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;
use SignNow\Rest\EntityManager\Annotation\ResponseType;
use JMS\Serializer\Annotation as Serializer;
use SignNow\Rest\Service\Serializer\Type\File;

/**
 * Class FileUpload
 *
 * @HttpEntity("document", idProperty="")
 * @GuzzleRequestBody("multipart")
 * @ResponseType("Tests\Fixtures\Entity\StatusResponse")
 *
 * @package Tests\Fixtures\Entity\Upload
 */
class FileUpload extends Entity
{
    /**
     * @Serializer\Type("SignNow\Rest\Service\Serializer\Type\File")
     * @var File
     */
    private $file;
    
    /**
     * FileUpload constructor.
     *
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }
}
