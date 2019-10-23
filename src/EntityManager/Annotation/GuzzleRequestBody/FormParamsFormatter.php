<?php
declare(strict_types=1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;

use GuzzleHttp\RequestOptions;

/**
 * Class FormParamsFormatter
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
 */
class FormParamsFormatter extends Formatter
{
    /**
     * @return string
     */
    public function getGuzzleOptionKey(): string
    {
        return RequestOptions::FORM_PARAMS;
    }
    
    /**
     * @param array $content
     *
     * @return mixed
     */
    public function prepareBody(array $content = [])
    {
        return $this->filterRequest($content);
    }
}
