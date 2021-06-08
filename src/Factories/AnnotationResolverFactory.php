<?php
declare(strict_types = 1);

namespace SignNow\Rest\Factories;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\Reader;
use SignNow\Rest\EntityManager\AnnotationFactory;
use SignNow\Rest\EntityManager\AnnotationResolver;

/**
 * Class ResolverFactory
 *
 * @package SignNow\Rest\Factories
 */
class AnnotationResolverFactory
{
    /**
     * @var AnnotationFactory|null
     */
    private $annotationFactory;
    
    /**
     * @var Reader|null
     */
    private $reader;
    
    /**
     * AnnotationResolverFactory constructor.
     *
     * @param AnnotationFactory $annotationFactory
     * @param Reader            $reader
     */
    public function __construct(AnnotationFactory $annotationFactory = null, Reader $reader = null)
    {
        $this->annotationFactory = $annotationFactory ?: new AnnotationFactory();
        $this->reader = $reader ?: new AnnotationReader();
    }
    
    /**
     * @return AnnotationResolver
     */
    public function create(): AnnotationResolver
    {
        return new AnnotationResolver($this->reader, $this->annotationFactory);
    }
}
