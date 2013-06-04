<?php

namespace SpiffyConnect\Client\Oauth1;

use Zend\Stdlib\AbstractOptions;

class ClientOptions extends AbstractOptions
{
    const SIGNATURE_HMAC_SHA1 = 'HMAC-SHA1';
    const SIGNATURE_RSA_SHA1  = 'RSA-SHA1';

    /**
     * @var array
     */
    protected $validSignatureMethods = array(
        self::SIGNATURE_HMAC_SHA1
    );

    /**
     * @var string
     */
    protected $redirectUri;

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
    protected $signatureMethod = self::SIGNATURE_HMAC_SHA1;

    /**
     * @var string
     */
    protected $version = '1.0';

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
     * @param string $signatureMethod
     * @return ClientOptions
     */
    public function setSignatureMethod($signatureMethod)
    {
        if (!in_array($signatureMethod, $this->validSignatureMethods)) {
            // todo: throw exception
        }
        $this->signatureMethod = $signatureMethod;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignatureMethod()
    {
        return $this->signatureMethod;
    }

    /**
     * @param string $version
     * @return ClientOptions
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}