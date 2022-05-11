<?php
declare(strict_types = 1);

namespace SignNow\Rest\Service\Request\Handler;

use Exception;
use GuzzleHttp\Exception\RequestException;
use SignNow\Rest\Entity\Error;

/**
 * Class DefaultErrorHandler
 *
 * @package SignNow\Rest\Service\Request\Handler
 */
class DefaultErrorHandler implements ErrorHandlerInterface
{
    /**
     * @param Exception $exception
     *
     * @return Error
     */
    public function handle(Exception $exception): Error
    {
        $error = new Error();
        $error->setResponse(($exception instanceof RequestException) ? $exception->getResponse() : null);
        $error->setMessage($exception->getMessage());
        
        return $error;
    }
}
