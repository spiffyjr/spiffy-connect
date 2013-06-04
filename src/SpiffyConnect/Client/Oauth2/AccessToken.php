<?php

namespace SpiffyConnect\Client\Oauth2;

use Zend\Http\Client as HttpClient;
use Zend\Stdlib\AbstractOptions;

class AccessToken extends AbstractOptions
{
    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var int
     */
    protected $creationTime;

    /**
     * @var int
     */
    protected $expireTime;

    public function __construct($options = null)
    {
        $this->creationTime = time();

        parent::__construct($options);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return null === $this->expireTime || (time() < $this->getCreationTime() + $this->getExpireTime());
    }

    /**
     * @return int
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }


    /**
     * @param int $expireTime
     * @return AccessToken
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
        return $this;
    }

    /**
     * @return int
     */
    public function getExpireTime()
    {
        return $this->expireTime;
    }

    /**
     * @param string $accessToken
     * @return AccessToken
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }
}