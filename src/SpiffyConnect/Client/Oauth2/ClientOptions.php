<?php

namespace SpiffyConnect\Client\Oauth2;

use Zend\Stdlib\AbstractOptions;

class ClientOptions extends AbstractOptions
{
    /**
     * {@inhericDoc}
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $clientId;

    /**
     * @var string
     */
    protected $clientSecret;

    /**
     * @var string
     */
    protected $redirectUri;

    /**
     * @var string
     */
    protected $responseFormat = 'json';

    /**
     * @var string
     */
    protected $accessTokenKey = 'access_token';

    /**
     * @var string
     */
    protected $errorKey = 'error';

    /**
     * @var string
     */
    protected $expireTimeKey = 'expires_in';

    /**
     * @var string
     */
    protected $scope;

    /**
     * @param string $redirectUri
     * @return ClientOptions
     */
    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @param string $clientSecret
     * @return ClientOptions
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientId
     * @return ClientOptions
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $responseFormat
     * @return ClientOptions
     */
    public function setResponseFormat($responseFormat)
    {
        $this->responseFormat = $responseFormat;
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseFormat()
    {
        return $this->responseFormat;
    }

    /**
     * @param string $accessTokenKey
     * @return ClientOptions
     */
    public function setAccessTokenKey($accessTokenKey)
    {
        $this->accessTokenKey = $accessTokenKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessTokenKey()
    {
        return $this->accessTokenKey;
    }

    /**
     * @param string $expireTimeKey
     * @return ClientOptions
     */
    public function setExpireTimeKey($expireTimeKey)
    {
        $this->expireTimeKey = $expireTimeKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpireTimeKey()
    {
        return $this->expireTimeKey;
    }

    /**
     * @param string $errorKey
     * @return ClientOptions
     */
    public function setErrorKey($errorKey)
    {
        $this->errorKey = $errorKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getErrorKey()
    {
        return $this->errorKey;
    }

    /**
     * @param string $scope
     * @return ClientOptions
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }
}