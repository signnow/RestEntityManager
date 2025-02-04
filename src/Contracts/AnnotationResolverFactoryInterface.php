<?php
declare(strict_types = 1);

namespace SignNow\Rest\Contracts;

use SignNow\Rest\EntityManager\AnnotationResolver;

interface AnnotationResolverFactoryInterface
{
    public function create(): AnnotationResolver;
}
