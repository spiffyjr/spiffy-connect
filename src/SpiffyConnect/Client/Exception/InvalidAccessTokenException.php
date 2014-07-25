<?php

namespace SpiffyConnect\Client\Exception;

use SpiffyConnect\Exception;

class InvalidAccessTokenException extends Exception\InvalidArgumentException
    implements ExceptionInterface
{
    /**
     * @var string
     */
    protected $body;

    /**
     * @param string $msg
     * @param string $body
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(
        $msg = "the specified access token is invalid",
        $body = "",
        $code = 0,
        \Exception $previous = null
    ) {
        $this->body = $body;
        parent::__construct($msg, $code, $previous);
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
