<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;

/**
 * Interface FormatInterface
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
 */
interface FormatInterface
{
    public const FORMAT_JSON = 'json';
    public const FORMAT_MULTIPART = 'multipart';
    public const FORMAT_FORM_PARAMS = 'form_params';
    
    /**
     * @return string
     */
    public function getGuzzleOptionKey(): string;
    
    /**
     * @param array $content
     *
     * @return mixed
     */
    public function prepareBody(array $content = []);
}
