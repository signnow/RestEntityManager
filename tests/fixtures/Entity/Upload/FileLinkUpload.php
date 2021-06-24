<?php
declare(strict_types = 1);

namespace Tests\Fixtures\Entity\Upload;

use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;
use SignNow\Rest\EntityManager\Annotation\ResponseType;
use JMS\Serializer\Annotation as Serializer;
use SignNow\Rest\Service\Serializer\Type\FileLink;

/**
 * Class FileLinkUpload
 *
 * @HttpEntity("document", idProperty="")
 * @GuzzleRequestBody("multipart")
 * @ResponseType("Tests\Fixtures\Entity\StatusResponse")
 *
 * @package Tests\Fixtures\Entity\Upload
 */
class FileLinkUpload extends Entity
{
    /**
     * @Serializer\Type("SignNow\Rest\Service\Serializer\Type\FileLink")
     * @var FileLink
     */
    private $fileLink;
    
    /**
     * FileLinkUpload constructor.
     *
     * @param FileLink $fileLink
     */
    public function __construct(FileLink $fileLink)
    {
        $this->fileLink = $fileLink;
    }
}
