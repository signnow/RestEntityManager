<?php
declare(strict_types = 1);

namespace Tests\Unit\EntityManager\Annotation;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody;

/**
 * Class GuzzleRequestBodyTest
 *
 * @coversDefaultClass \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody
 *
 * @package Tests\Unit\EntityManager\Annotation
 */
class GuzzleRequestBodyTest extends TestCase
{
    /**
     * @covers ::initialize
     * @covers ::__construct
     * @covers ::getFormatterFactory
     */
    public function testInvalidArgumentExceptionOnInitialize()
    {
        $this->expectException(InvalidArgumentException::class);
    
        $response = new GuzzleRequestBody([]);
    
        $response->initialize();
    }
}
