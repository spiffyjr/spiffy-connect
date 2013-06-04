<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class InvalidResponseFormatException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
    public function __construct($type, $code = 0, \Exception $previous = null)
    {
        parent::__construct("unknown response type: {$type}", $code, $previous);
    }
}