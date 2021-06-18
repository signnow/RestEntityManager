<?php
declare(strict_types = 1);

namespace Tests\Unit\EntityManager\Annotation\GuzzleRequestBody;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormatInterface;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormatterFactory;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormParamsFormatter;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\JsonFormatter;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\MultipartFormatter;

/**
 * Class FormatterFactoryTest
 *
 * @coversDefaultClass \SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\FormatterFactory
 *
 * @package Tests\Unit\EntityManager\Annotation\GuzzleRequestBody
 */
class FormatterFactoryTest extends TestCase
{
    /**
     * @param string $type
     * @param string $formatter
     *
     * @covers ::create
     * @dataProvider getFormatterTypeDataProvider
     */
    public function testCreate(string $type, string $formatter): void
    {
        $factory = new FormatterFactory();
        
        $this->assertInstanceOf($formatter, $factory->create($type));
    }
    
    /**
     * @covers ::create
     */
    public function testInvalidFormatterTypeGiven(): void
    {
        $this->expectException(RuntimeException::class);
        
        $factory = new FormatterFactory();
        
        $factory->create('unsupported_type');
    }
    
    /**
     * @return array[]
     */
    public function getFormatterTypeDataProvider(): array
    {
        return [
            [
                'type' => FormatInterface::FORMAT_JSON,
                'formatter' => JsonFormatter::class
            ],
            [
                'type' => FormatInterface::FORMAT_MULTIPART,
                'formatter' => MultipartFormatter::class
            ],
            [
                'type' => FormatInterface::FORMAT_FORM_PARAMS,
                'formatter' => FormParamsFormatter::class
            ],
        ];
    }
}
