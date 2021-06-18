<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;

use GuzzleHttp\RequestOptions;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart\FileLinkItem;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart\Item;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart\SplFileInfoItem;
use SignNow\Rest\Service\Serializer\Type\FileLink;
use SignNow\Rest\Service\Serializer\Type\File;
use SplFileInfo;

/**
 * Class MultipartFormatter
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
 */
class MultipartFormatter extends Formatter
{
    /**
     * @var array
     */
    protected $unprocessableTypes = [];
    
    /**
     * @return string
     */
    public function getGuzzleOptionKey(): string
    {
        return RequestOptions::MULTIPART;
    }
    
    /**
     * @param array $content
     *
     * @return mixed
     */
    public function prepareBody(array $content = [])
    {
        return $this->createMultipartRequest($this->filterRequest($content));
    }
    
    /**
     * @param array $response
     *
     * @return array
     */
    private function createMultipartRequest(array $response = [])
    {
        $result = [];
        foreach ($response as $name => $value) {
            $part = $this->createPart($name, $value);
            
            $result = array_merge($result, is_array($part) ? $part : [$part]);
        }
        
        return array_map(function (Item $item) {
            return $item->toArray();
        }, $result);
    }
    
    /**
     * @param $name
     * @param $value
     *
     * @return array|Item
     */
    private function createPart($name, $value)
    {
        if (is_array($value)) {
            $parts = [];
            foreach ($value as $key => $content) {
                $part = $this->createPart(sprintf("%s[%s]", $name, $key), $content);
                
                $parts = array_merge($parts, is_array($part) ? $part : [$part]);
            }
            
            return $parts;
        }
    
        switch (true) {
            case $value instanceof SplFileInfo:
                $item = new SplFileInfoItem($name, $value, [], $value->getFilename());
                break;
            case $value instanceof FileLink:
                $item = new FileLinkItem($name, $value->getLink(), [], $value->getFilename());
                break;
            case $value instanceof File:
                $item = new Item($name, $value->getContent(), [], $value->getFilename());
                break;
            default:
                $item = new Item($name, $value);
                break;
        }
    
        return $item;
    }
}
