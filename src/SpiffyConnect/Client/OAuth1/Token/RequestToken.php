<?php

namespace SpiffyConnect\Client\OAuth1\Token;

class RequestToken implements TokenInterface
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $secret;

    /**
     * @var bool
     */
    protected $callbackConfirmed;

    /**
     * @param string $secret
     * @return RequestToken
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $token
     * @return RequestToken
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param bool $callbackConfirmed
     * @return RequestToken
     */
    public function setCallbackConfirmed($callbackConfirmed)
    {
        if (is_string($callbackConfirmed)) {
            if ('true' === strtolower($callbackConfirmed)) {
                $callbackConfirmed = true;
            } else {
                $callbackConfirmed = false;
            }
        }
        $this->callbackConfirmed = $callbackConfirmed;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCallbackConfirmed()
    {
        return $this->callbackConfirmed;
    }
}