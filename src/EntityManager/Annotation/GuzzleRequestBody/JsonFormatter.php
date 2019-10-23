<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;

use GuzzleHttp\RequestOptions;
use JMS\Serializer\Exception\RuntimeException;

/**
 * Class JsonFormatter
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
 */
class JsonFormatter extends Formatter
{
    /**
     * @return string
     */
    public function getGuzzleOptionKey(): string
    {
        return RequestOptions::BODY;
    }
    
    /**
     * @param array $content
     *
     * @return string
     */
    public function prepareBody(array $content = [])
    {
        $result = @json_encode($this->filterRequest($content));
        
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return $result;
            
            case JSON_ERROR_UTF8:
                throw new RuntimeException(
                    'Your data could not be encoded because it contains invalid UTF8 characters.'
                );
            
            default:
                throw new RuntimeException(
                    sprintf('An error occurred while encoding your data (error code %d).', json_last_error())
                );
        }
    }
}
