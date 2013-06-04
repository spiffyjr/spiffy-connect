<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class InvalidAccessTokenException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
    public function __construct($msg = "the specified access token is invalid", $code = 0, \Exception $previous = null)
    {
        parent::__construct($msg, $code, $previous);
    }
}