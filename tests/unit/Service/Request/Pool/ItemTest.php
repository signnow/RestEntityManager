<?php
declare(strict_types = 1);

namespace Tests\Unit\Service\Request\Pool;

use PHPUnit\Framework\TestCase;
use SignNow\Rest\Entity\Error;
use SignNow\Rest\Service\Request\Pool\Item;
use Tests\Fixtures\Entity\FileEntity;

/**
 * Class ItemTest
 *
 * @package Tests\Unit\Service\Request\Pool
 */
class ItemTest extends TestCase
{
    /**
     * @return void
     */
    public function testGetError(): void
    {
        $item = new Item();
        $item->setError(new Error());
        
        $this->assertTrue($item->hasError());
        $this->assertNotEmpty($item->getError());
    }
    
    /**
     * @return void
     */
    public function testGetEntity(): void
    {
        $item = new Item();
        $item->setEntity(new FileEntity());
        
        $this->assertNotEmpty($item->getEntity());
    }
}
