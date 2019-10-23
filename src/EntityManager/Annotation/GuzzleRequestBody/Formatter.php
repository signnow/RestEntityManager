<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;

use SplFileInfo;

/**
 * Class Formatter
 *
 * @package SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
 */
abstract class Formatter implements FormatInterface
{
    /**
     * @var array
     */
    protected $unprocessableTypes = [SplFileInfo::class];
    
    /**
     * @param array $content
     *
     * @return array
     */
    protected function filterRequest(array $content = []): array
    {
        return array_filter($content, function ($value) {
            
            foreach ($this->unprocessableTypes as $type) {
                if ($value instanceof $type) {
                    return false;
                }
            }
            
            return true;
        });
    }
}
