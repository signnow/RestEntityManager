<?php
declare(strict_types = 1);

namespace Tests\Fixtures\Entity\Upload;

use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;
use SignNow\Rest\EntityManager\Annotation\ResponseType;
use JMS\Serializer\Annotation as Serializer;
use SplFileInfo;

/**
 * Class SplFileInfoUpload
 *
 * @HttpEntity("document", idProperty="")
 * @GuzzleRequestBody("multipart")
 * @ResponseType("Tests\Fixtures\Entity\StatusResponse")
 *
 * @package Tests\Fixtures\Entity\Upload
 */
class SplFileInfoUpload extends Entity
{
    /**
     * @Serializer\Type("SplFileInfo")
     * @var SplFileInfo
     */
    private $file;
    
    /**
     * @Serializer\Type("array")
     * @var array
     */
    private $tags;
    
    /**
     * SplFileInfoUpload constructor.
     *
     * @param SplFileInfo $file
     * @param array       $tags
     */
    public function __construct(SplFileInfo $file, array $tags = [])
    {
        $this->file = $file;
        $this->tags = $tags;
    }
}
