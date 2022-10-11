<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Serializer\Type;

/**
 * Class File
 *
 * @package SignNow\Rest\Service\Serializer\Type
 */
class File
{
    /**
     * @var string
     */
    private $content;
    
    /**
     * @var string
     */
    private $filename;
    
    /**
     * File constructor.
     *
     * @param string $content
     * @param string $filename
     */
    public function __construct(string $content, string $filename)
    {
        $this->content = $content;
        $this->filename = $filename;
    }
    
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
    
    /**
     * @return string
     */
    public function getFilename(): string
    {
        return str_replace('"', '', $this->filename);
    }
}
