<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class InvalidSignatureMethod extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
    public function __construct($method, $code = 0, \Exception $previous = null)
    {
        parent::__construct("invalid signature method: {$method}", $code, $previous);
    }
}