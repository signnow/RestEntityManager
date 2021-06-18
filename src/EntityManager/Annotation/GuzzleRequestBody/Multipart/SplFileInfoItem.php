<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart;

use \SplFileInfo;

/**
 * Class SplFileInfoItem
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart
 */
class SplFileInfoItem extends Item
{
    /**
     * SplFileInfoItem constructor.
     *
     * @param string      $name
     * @param SplFileInfo $fileInfo
     * @param array       $headers
     * @param string|null $filename
     */
    public function __construct(string $name, SplFileInfo $fileInfo, array $headers = [], ?string $filename = null)
    {
        $content = fopen($fileInfo->getRealPath(), 'r');
    
        $meta = stream_get_meta_data($content);
        if ($meta['seekable'] === false) {
            fclose($content);
            $content = file_get_contents($fileInfo->getRealPath());
        }
        
        parent::__construct($name, $content, $headers, $filename);
    }
}
