<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart;

/**
 * Class Item
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart
 */
class Item
{
    /**
     * @var string
     */
    private $name;
    
    /**
     * @var
     */
    private $content;
    
    /**
     * @var null
     */
    private $filename;
    
    /**
     * @var array
     */
    private $headers;
    
    /**
     * Item constructor.
     *
     * @param string      $name
     * @param             $content
     * @param array       $headers
     * @param string|null $filename
     */
    public function __construct(string $name, $content, array $headers = [], ?string $filename = null)
    {
        $this->name = $name;
        $this->content = $content;
        $this->filename = $filename;
        $this->headers = $headers;
    }
    
    /**
     * @return array
     */
    public function toArray(): array
    {
        $part = [
            'name' => $this->name,
            'contents' => $this->content,
        ];
        
        if (!empty($this->filename)) {
            $part['filename'] = $this->filename;
        }
        
        if (!empty($this->headers)) {
            $part['headers'] = $this->headers;
        }
        
        return $part;
    }
}
