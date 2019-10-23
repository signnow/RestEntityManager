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
     * FileLink constructor.
     *
     * @param string $link
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }
    
    /**
     * @return string
     */
    public function getFilename(): string
    {
        return explode("?", basename($this->link))[0];
    }
    
    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }
}
