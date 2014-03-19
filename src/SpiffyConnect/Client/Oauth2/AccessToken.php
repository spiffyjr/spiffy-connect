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
    protected $expiresIn;

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
        return null === $this->expiresIn || (time() < $this->getCreationTime() + $this->getExpiresIn());
    }

    /**
     * @return int
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }


    /**
     * @param int $expiresIn
     * @return AccessToken
     */
    public function setExpiresIn($expiresIn)
    {
        $this->expiresIn = $expiresIn;
    }

    /**
     * @return int
     */
    public function getExpiresIn()
    {
        return $this->expiresIn;
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