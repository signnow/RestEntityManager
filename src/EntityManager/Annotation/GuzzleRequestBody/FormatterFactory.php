<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;

use RuntimeException;

/**
 * Class FormatterFactory
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
 */
class FormatterFactory
{
    /**
     * @param string $type
     *
     * @return FormatInterface
     */
    public function create(string $type): FormatInterface
    {
        switch ($type) {
            case FormatInterface::FORMAT_JSON:
                $formatter = new JsonFormatter();
                break;
            case FormatInterface::FORMAT_MULTIPART:
                $formatter = new MultipartFormatter();
                break;
            case FormatInterface::FORMAT_FORM_PARAMS:
                $formatter = new FormParamsFormatter();
                break;
            default:
                throw new RuntimeException('Unsupported format type');
        }
        
        return $formatter;
    }
}
