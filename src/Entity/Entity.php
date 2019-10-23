<?php
declare(strict_types = 1);

namespace SignNow\Rest\Entity;

/**
 * Class Entity
 *
 * @package SignNow\Rest\Entity
 */
class Entity
{
    /**
     * @return string
     */
    final public function getEntityClass()
    {
        return static::class;
    }
}
