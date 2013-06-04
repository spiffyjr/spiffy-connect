<?php

namespace SpiffyConnect\Provider\Exception;

use SpiffyConnect\Exception;

class InvalidProviderException extends \Exception implements ExceptionInterface
{
    public function __construct(
        $message = 'Provider must implement SpiffyConnect\Provider\ProviderInterface',
        $code = 0,
        $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}