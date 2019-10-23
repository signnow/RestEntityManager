<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager;

use InvalidArgumentException;
use SignNow\Rest\EntityManager\Annotation\Annotation;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;
use SignNow\Rest\EntityManager\Annotation\ResponseType;

/**
 * Class AnnotationFactory
 *
 * @package SignNow\Rest\EntityManager
 */
class AnnotationFactory
{
    /**
     * @param string $annotationClass
     *
     * @return ResponseType|GuzzleRequestBody
     */
    public function createDefaultAnnotation(string $annotationClass): Annotation
    {
        switch ($annotationClass) {
            case ResponseType::class:
                $annotation = $this->getDefaultResponseType();
                break;
            case GuzzleRequestBody::class:
                $annotation = $this->getDefaultRequestBody();
                break;
            default:
                throw new InvalidArgumentException('Default annotation does not configured');
        }
        
        return $annotation;
    }
    
    /**
     * @return ResponseType
     */
    public function getDefaultResponseType(): ResponseType
    {
        return new ResponseType(['value' => ResponseType::DEFAULT_TYPE]);
    }
    
    /**
     * @return GuzzleRequestBody
     */
    public function getDefaultRequestBody(): GuzzleRequestBody
    {
        return new GuzzleRequestBody(['value' => GuzzleRequestBody::DEFAULT_FORMAT]);
    }
}
