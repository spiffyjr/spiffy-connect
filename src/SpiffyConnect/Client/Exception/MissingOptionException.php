<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class MissingOptionException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
    public function __construct($option, $code = 0, \Exception $previous = null)
    {
        parent::__construct("missing required option: {$option}", $code, $previous);
    }
}