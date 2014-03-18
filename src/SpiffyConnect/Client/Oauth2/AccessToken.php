<?php

namespace SpiffyConnect\Client\OAuth2;

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
     * @var string
     */
    protected $refreshToken;

    /**
     * @var int
     */
    protected $expireTime;

    /**
     * @param null $options
     */
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
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $refreshToken
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;
    }

    /**
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}