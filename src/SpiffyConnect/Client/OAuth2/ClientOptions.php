<?php

namespace SpiffyConnect\Client\OAuth2;

use SpiffyConnect\Client\AbstractOptions;

class ClientOptions extends AbstractOptions
{
    /**
     * {@inhericDoc}
     */
    protected $__strictMode__ = false;

    /**
     * @var string
     */
    protected $oauthEndpoint = 'oauth';

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
    protected $accessTokenKey = 'access_token';

    /**
     * @var string
     */
    protected $refreshTokenKey = 'refresh_token';

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
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param string $accessTokenKey
     * @return ClientOptions
     */
    public function setAccessTokenKey($accessTokenKey)
    {
        $this->accessTokenKey = $accessTokenKey;
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
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $refreshTokenKey
     */
    public function setRefreshTokenKey($refreshTokenKey)
    {
        $this->refreshTokenKey = $refreshTokenKey;
    }

    /**
     * @return string
     */
    public function getRefreshTokenKey()
    {
        return $this->refreshTokenKey;
    }

    /**
     * @param string $oauthEndpoint
     */
    public function setOAuthEndpoint($oauthEndpoint)
    {
        $this->oauthEndpoint = $oauthEndpoint;
    }

    /**
     * @return string
     */
    public function getOAuthEndpoint()
    {
        return $this->oauthEndpoint;
    }
}
