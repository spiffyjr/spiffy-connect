<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class ResponseErrorException extends Exception\RuntimeException
    implements ExceptionInterface
{
    public function __construct($msg, $code = 0, \Exception $previous = null)
    {
        parent::__construct("{$code}: ${msg}", $code, $previous);
    }
}