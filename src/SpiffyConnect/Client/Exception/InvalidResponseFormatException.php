<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class InvalidResponseFormatException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
    /**
     * @var string
     */
    protected $body;

    /**
     * @param string $type
     * @param int $body
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($type, $body, $code = 0, \Exception $previous = null)
    {
        $this->body = $body;
        parent::__construct("unknown response type: {$type}", $code, $previous);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
