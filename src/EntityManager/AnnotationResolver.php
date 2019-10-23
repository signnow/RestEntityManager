<?php
declare(strict_types = 1);

namespace SignNow\Rest\EntityManager;

use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionException;
use SignNow\Rest\Entity\Entity;
use SignNow\Rest\EntityManager\Annotation\Annotation;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;
use SignNow\Rest\EntityManager\Annotation\ResponseType;

/**
 * Class AnnotationResolver
 *
 * @package SignNow\Rest\EntityManager
 */
class AnnotationResolver
{
    /**
     * @var Reader
     */
    private $reader;
    
    /**
     * @var Annotation
     */
    private $annotations;
    
    /**
     * @var AnnotationFactory
     */
    private $factory;
    
    /**
     * AnnotationResolver constructor.
     *
     * @param Reader            $reader
     * @param AnnotationFactory $factory
     */
    public function __construct(Reader $reader, AnnotationFactory $factory)
    {
        $this->reader = $reader;
        $this->factory = $factory;
    }
    
    /**
     * @param Entity $entity
     * @param mixed  ...$args
     *
     * @return HttpEntity
     * @throws ReflectionException
     */
    public function getHttpEntity(Entity $entity, ...$args): HttpEntity
    {
        return $this->resolve($entity, HttpEntity::class, ...$args);
    }
    
    /**
     * @param Entity $entity
     *
     * @return ResponseType|null
     * @throws ReflectionException
     */
    public function getResponseType(Entity $entity): ?ResponseType
    {
        return $this->resolve($entity, ResponseType::class);
    }
    
    /**
     * @param Entity $entity
     *
     * @return GuzzleRequestBody|null
     * @throws ReflectionException
     */
    public function getRequestBody(Entity $entity): ?GuzzleRequestBody
    {
        return $this->resolve($entity, GuzzleRequestBody::class);
    }
    
    /**
     * @param Entity $entity
     * @param string $annotationClass
     * @param array  $args
     *
     * @return mixed
     * @throws ReflectionException
     */
    protected function resolve(Entity $entity, string $annotationClass, ...$args): ?Annotation
    {
        /** @var Annotation $annotation */
        $annotation = $this->resolveAnnotation($entity->getEntityClass(), $annotationClass);
        
        if (!empty($annotation)) {
            $annotation->setOwner($entity)->initialize(...$args);
        }
        
        return $annotation;
    }
    
    /**
     * @param string $entityClass
     * @param string $annotationClass
     *
     * @return Annotation|null
     * @throws ReflectionException
     */
    private function resolveAnnotation(string $entityClass, string $annotationClass): ?Annotation
    {
        if (isset($this->annotations[$entityClass]) === false) {
            $this->annotations[$entityClass] = [];
        }
        
        if (isset($this->annotations[$entityClass][$annotationClass]) === false) {
            $class = new ReflectionClass($entityClass);
            $annotation = $this->reader->getClassAnnotation($class, $annotationClass);
            
            if (empty($annotation)) {
                $annotation = $this->factory->createDefaultAnnotation($annotationClass);
            }
            
            $this->annotations[$entityClass][$annotationClass] = $annotation;
        }
        
        return $this->annotations[$entityClass][$annotationClass];
    }
}
