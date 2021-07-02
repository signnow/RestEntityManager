<?php
declare(strict_types = 1);

namespace Tests\Fixtures\Entity;

use JMS\Serializer\Annotation as Serializer;

/**
 * Class StatusResponse
 *
 * @package Tests\Fixtures\Entity
 */
class StatusResponse
{
    /**
     * @Serializer\Type("string")
     * @var string
     */
    private $status;
    
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
