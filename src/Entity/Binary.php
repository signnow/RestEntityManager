<?php
declare(strict_types = 1);

namespace SignNow\Rest\Entity;

/**
 * Class Binary
 *
 * @package SignNow\Rest\Entity
 */
class Binary
{
    /**
     * @var string
     */
    private $content;
    
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
    
    /**
     * @param string $content
     *
     * @return Binary
     */
    public function setContent(string $content): self
    {
        $this->content = $content;
        
        return $this;
    }
}
