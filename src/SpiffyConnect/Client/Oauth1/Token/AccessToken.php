<?php

namespace SpiffyConnect\Client\Oauth1\Token;

class AccessToken implements TokenInterface
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
     * @param string $secret
     * @return AccessToken
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
     * @return AccessToken
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
}