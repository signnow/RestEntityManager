<?php
declare(strict_types = 1);

namespace Tests\Unit\EntityManager\Annotation;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SignNow\Rest\EntityManager\Annotation\ResponseType;

/**
 * Class ResponseTypeTest
 *
 * @coversDefaultClass \SignNow\Rest\EntityManager\Annotation\ResponseType
 *
 * @package Tests\Unit\EntityManager\Annotation
 */
class ResponseTypeTest extends TestCase
{
    /**
     * @covers ::initialize
     * @covers ::__construct
     */
    public function testInvalidArgumentExceptionOnInitialize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        $response = new ResponseType(['value' => 'invalid_type[]ffas342']);
        
        $response->initialize();
    }
}
