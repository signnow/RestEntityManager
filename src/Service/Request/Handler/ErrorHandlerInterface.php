<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Request\Handler;

use Exception;
use SignNow\Rest\Entity\Error;

/**
 * Interface ErrorHandlerInterface
 *
 * @package SignNow\Rest\Service\Request\Handler
 */
interface ErrorHandlerInterface
{
    /**
     * @param Exception $exception
     *
     * @return Error
     */
    public function handle(Exception $exception): Error;
}
