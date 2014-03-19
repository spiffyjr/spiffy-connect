<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class InvalidRequestFormatException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
}