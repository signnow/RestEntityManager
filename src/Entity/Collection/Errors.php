<?php
declare(strict_types = 1);

namespace SignNow\Rest\Entity\Collection;

use JMS\Serializer\Annotation as Serializer;
use SignNow\Rest\Entity\Error;

/**
 * Class Errors
 *
 * @package SignNow\Rest\Entity
 *
 * @Serializer\ExclusionPolicy("all")
 */
class Errors
{
    /**
     * @var array
     *
     * @Serializer\Expose()
     * @Serializer\Type("array<SignNow\Rest\Entity\Error>")
     */
    protected $errors;
    
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
    
    /**
     * @param $index
     *
     * @return Error|null
     */
    public function getError($index): ?Error
    {
        return isset($this->errors[$index]) ? $this->errors[$index] : null;
    }
    
    /**
     * @param int   $index
     * @param Error $error
     */
    public function push(int $index, Error $error)
    {
        $this->errors[$index] = $error;
    }
    
    /**
     * @return string
     */
    public function getFullMessage(): string
    {
        $messagesArr = array_map(function (Error $error) {
            return $error->getMessage();
        }, $this->errors);
        
        return implode("\n", $messagesArr);
    }
    
    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
    
    /**
     * @return void
     */
    public function clear(): void
    {
        $this->errors = [];
    }
}
