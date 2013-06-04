<?php

namespace SpiffyConnect\Provider\Oauth2;

use SpiffyConnect\Client\Oauth2\ClientOptions;

class ProviderOptions extends ClientOptions
{
    /**
     * @var string
     */
    protected $accessTokenUri;

    /**
     * @var string
     */
    protected $authorizationCodeUri;

    /**
     * @var array
     */
    protected $accessTokenParameters = array();

    /**
     * @var array
     */
    protected $authorizationCodeParameters = array();

    /**
     * @param array $accessTokenParameters
     * @return ProviderOptions
     */
    public function setAccessTokenParameters($accessTokenParameters)
    {
        $this->accessTokenParameters = $accessTokenParameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getAccessTokenParameters()
    {
        return $this->accessTokenParameters;
    }

    /**
     * @param string $accessTokenUri
     * @return ProviderOptions
     */
    public function setAccessTokenUri($accessTokenUri)
    {
        $this->accessTokenUri = $accessTokenUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessTokenUri()
    {
        return $this->accessTokenUri;
    }

    /**
     * @param array $authorizationCodeParameters
     * @return ProviderOptions
     */
    public function setAuthorizationCodeParameters($authorizationCodeParameters)
    {
        $this->authorizationCodeParameters = $authorizationCodeParameters;
        return $this;
    }

    /**
     * @return array
     */
    public function getAuthorizationCodeParameters()
    {
        return $this->authorizationCodeParameters;
    }

    /**
     * @param string $authorizationCodeUri
     * @return ProviderOptions
     */
    public function setAuthorizationCodeUri($authorizationCodeUri)
    {
        $this->authorizationCodeUri = $authorizationCodeUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorizationCodeUri()
    {
        return $this->authorizationCodeUri;
    }
}