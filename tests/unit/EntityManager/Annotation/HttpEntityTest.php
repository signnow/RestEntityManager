<?php
declare(strict_types = 1);

namespace Tests\Unit\EntityManager\Annotation;

use BadMethodCallException;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SignNow\Rest\EntityManager\Annotation\HttpEntity;

/**
 * Class HttpEntityTest
 *
 * @coversDefaultClass \SignNow\Rest\EntityManager\Annotation\HttpEntity
 *
 * @package Tests\Unit\EntityManager\Annotation
 */
class HttpEntityTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testInvalidPropertyOnConstruct(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        new HttpEntity(['unsupportedProperty' => 'some value']);
    }
    
    /**
     * @param string $uri
     * @param array  $uriParams
     * @param string $expectedUri
     *
     * @dataProvider getUriDataProvider
     * @covers ::getEndpoint
     * @covers ::__construct
     * @covers ::replaceUriParams
     */
    public function testGetEndpoint(string $uri, array $uriParams, string $expectedUri): void
    {
        $entity = new HttpEntity([
            'uri' => $uri,
            'uriParams' => $uriParams,
        ]);
        
        $this->assertEquals($expectedUri, $entity->getEndpoint());
    }
    
    /**
     * @covers ::getIdValue
     * @covers ::initialize
     * @covers ::__construct
     */
    public function testNotExistingIdGetter(): void
    {
        $this->expectException(BadMethodCallException::class);
        
        $entity = new HttpEntity([]);
        
        $entity->initialize();
    }
    
    /**
     * @covers ::getIdValue
     * @covers ::initialize
     * @covers ::__construct
     */
    public function testEmptyIdProperty(): void
    {
        $entity = new HttpEntity([
            'idProperty' => '',
        ]);
    
        $entity->initialize();
        
        $this->assertAttributeEmpty('uriParams', $entity);
    }
    
    /**
     * @return array[]
     */
    public function getUriDataProvider(): array
    {
        return [
            [
                'uri' => 'users',
                'uriParams' => [],
                'expectedUri' => 'users',
            ],
            [
                'uri' => '/users/',
                'uriParams' => [],
                'expectedUri' => 'users',
            ],
            [
                'uri' => 'users/{user}',
                'uriParams' => ['user' => 'Jack'],
                'expectedUri' => 'users/Jack',
            ],
            [
                'uri' => 'users/{user}/settings/{setting}',
                'uriParams' => ['user' => 'Jack', 'setting' => 'date_format'],
                'expectedUri' => 'users/Jack/settings/date_format',
            ],
        ];
    }
}
