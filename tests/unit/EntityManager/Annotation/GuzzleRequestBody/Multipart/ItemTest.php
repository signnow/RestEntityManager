<?php
declare(strict_types = 1);

namespace Tests\Unit\EntityManager\Annotation\GuzzleRequestBody\Multipart;

use PHPUnit\Framework\TestCase;
use SignNow\Rest\EntityManager\Annotation\GuzzleRequestBody\Multipart\Item;

/**
 * Class ItemTest
 * 
 * @package Tests\Unit\EntityManager\Annotation\GuzzleRequestBody\Multipart
 */
class ItemTest extends TestCase
{
    /**
     * @return void
     */
    public function testToArray(): void
    {
        $item = new Item('name', 'content', ['header' => 'value'], 'filename');
        $toArray = $item->toArray();
        
        $this->assertArrayHasKey('name', $toArray);
        $this->assertArrayHasKey('contents', $toArray);
        $this->assertArrayHasKey('filename', $toArray);
        $this->assertArrayHasKey('headers', $toArray);
    }
}
