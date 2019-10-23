<?php
declare(strict_types=1);

namespace SignNow\Rest\Service\Request;

use SignNow\Rest\Service\Request\Pool\Item;
use GuzzleHttp\ClientInterface;

/**
 * Interface PoolInterface
 *
 * @package src\Contracts
 */
interface PoolInterface
{
    /**
     * @param Item $item
     *
     * @return PoolInterface
     */
    public function add(Item $item): self;

    /**
     * @param int|null $concurrencyLimit maximum number of parallel calls
     *
     * @return PoolInterface
     */
    public function send(int $concurrencyLimit = null): self;
    
    /**
     * @return Item[]
     */
    public function getItems(): array;
}
