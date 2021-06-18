<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Serializer\Type;

/**
 * Class FileLink
 *
 * @package SignNow\Rest\Service\Serializer\Type
 */
class FileLink
{
    /**
     * @var string
     */
    private $link;
    
    /**
     * @var string|null
     */
    private $filename;
    
    /**
     * FileLink constructor.
     *
     * @param string      $link
     * @param string|null $filename
     */
    public function __construct(string $link, string $filename = null)
    {
        $this->link = $link;
        $this->filename = $filename ?: explode("?", basename($this->link))[0];
    }
    
    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }
    
    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}
