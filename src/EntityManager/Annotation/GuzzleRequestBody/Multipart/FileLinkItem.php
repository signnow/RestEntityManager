<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart;

/**
 * Class FileLinkItem
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart
 */
class FileLinkItem extends Item
{
    /**
     * FileLinkItem constructor.
     *
     * @param string      $name
     * @param string      $link
     * @param array       $headers
     * @param string|null $filename
     */
    public function __construct(string $name, string $link, array $headers = [], ?string $filename = null)
    {
        $context = stream_context_create(['http' => ['method' => 'GET']]);
        $content = fopen($link, 'r', false, $context);
        
        $metadata = stream_get_meta_data($content);
        if ($metadata['seekable'] === false) {
            $content = file_get_contents($link);
        }
        
        parent::__construct($name, $content, $headers, $filename);
    }
}
