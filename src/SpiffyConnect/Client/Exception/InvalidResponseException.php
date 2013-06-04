<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class InvalidResponseException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
}