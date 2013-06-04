<?php

namespace SpiffyConnect\Client\Oauth1;

use SpiffyConnect\Client\AbstractClient;
use SpiffyConnect\Client\Exception;
use SpiffyConnect\Client\Oauth1\Token\AccessToken;
use SpiffyConnect\Client\Oauth1\Token\RequestToken;
use SpiffyConnect\Client\Oauth1\Token\TokenInterface;
use Zend\Http\Client as HttpClient;
use Zend\Http\PhpEnvironment\Request as PhpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Http\Request as HttpRequest;
use Zend\Uri\Uri;

class Client extends AbstractClient
{
    /**
     * Signifies valid keys for the authorization header.
     */
    const AUTH_HEADER_REGEX = '/^oauth_(?:callback|consumer_key|nonce|signature|signature_method|timestamp|token|version)/';

    /**
     * If set to true will use the values in debugNonce
     * and debugTimestamp for generating signatures so that
     * you can more easily test your application.
     *
     * @var bool
     */
    protected $debugMode = false;

    /**
     * @var string
     */
    protected $debugNonce;

    /**
     * @var int
     */
    protected $debugTimestamp;

    /**
     * @var array
     */
    protected $signatureClassmap = array(
        ClientOptions::SIGNATURE_HMAC_SHA1 => 'SpiffyConnect\Client\Oauth1\Signature\HmacSha1Signature'
    );

    /**
     * @param null $options
     */
    public function __construct($options = null)
    {
        $this->options = new ClientOptions($options);
    }

    /**
     * Sent an OAuth request.
     *
     * @param AccessToken $token
     * @param string $uri
     * @param string $method
     * @param array|null $params
     * @return HttpResponse
     */
    public function request(AccessToken $token, $uri, $method = HttpRequest::METHOD_GET, array $params = null)
    {
        return $this->prepareClient($uri, $method, $params, $token)->send();
    }

    /**
     * @param string $uri
     * @param string $method
     * @throws Exception\InvalidResponseException
     * @return RequestToken
     */
    public function getRequestToken($uri, $method = HttpRequest::METHOD_POST)
    {
        $client   = $this->prepareClient($uri, $method);
        $response = $client->send();
        $content  = $this->decodeResponse($response, 'querystring');

        if (!isset($content['oauth_token']) || !isset($content['oauth_token_secret'])) {
            throw new Exception\InvalidResponseException('response from request token server was invalid');
        }

        $confirmed = isset($content['oauth_callback_confirmed']) ? $content['oauth_callback_confirmed'] : false;

        if (is_string($confirmed)) {
            $confirmed = strtolower($confirmed);
            $confirmed = trim($confirmed);

            if ($confirmed === 'true') {
                $confirmed = true;
            } else {
                $confirmed = false;
            }
        }

        if (!$confirmed) {
            throw new Exception\InvalidResponseException('oauth_callback_confirmed was not true');
        }

        $token = new RequestToken();
        $token->setToken($content['oauth_token']);
        $token->setSecret($content['oauth_token_secret']);
        $token->setCallbackConfirmed($content['oauth_callback_confirmed']);

        return $token;
    }

    /**
     * @param string $uri
     * @param RequestToken $token
     * @param string $method
     * @return AccessToken
     */
    public function getAccessToken($uri, RequestToken $token, $method = HttpRequest::METHOD_POST)
    {
        $request  = new PhpRequest();
        $verifier = $request->getQuery('oauth_verifier');

        $client   = $this->prepareClient($uri, $method, array('oauth_verifier' => $verifier), $token);
        $response = $client->send();
        $response = $this->decodeResponse($response, 'querystring');

        $token = new AccessToken();
        $token->setToken($response['oauth_token']);
        $token->setSecret($response['oauth_token_secret']);

        return $token;
    }

    /**
     * Get the redirect response used for redirecting the client for authorization.
     *
     * @param string $uri
     * @param RequestToken $token
     * @return HttpResponse
     */
    public function getRedirectResponse($uri, RequestToken $token)
    {
        $uri = new Uri($uri);
        $uri->setQuery('oauth_token=' . $token->getToken());

        $response = new HttpResponse();
        $response->getHeaders()->addHeaderLine('Location', $uri->toString());
        $response->setStatusCode(302);

        return $response;
    }

    /**
     * Sets an HttpRequest authorization header line.
     *
     * @param HttpRequest $request
     * @param TokenInterface $token
     * @return \Zend\Http\Headers
     */
    public function setRequestAuthorizationHeader(HttpRequest $request, TokenInterface $token = null)
    {
        $extras = array();
        switch ($request->getMethod()) {
            case HttpRequest::METHOD_GET:
                $extras = $request->getQuery()->toArray();
                break;
            case HttpRequest::METHOD_POST:
                $extras = $request->getPost()->toArray();
        }

        $header = $this->generateAuthorizationHeader(
            $request->getUriString(),
            $token,
            $request->getMethod(),
            $extras
        );

        return $request->getHeaders()->addHeaderLine('Authorization', $header);
    }

    /**
     * Generate an authorization header.
     *
     * @param string $uri
     * @param TokenInterface $token
     * @param string $method
     * @param null|array $extraParams
     * @param null|string $realm
     * @return string
     */
    public function generateAuthorizationHeader(
        $uri,
        TokenInterface $token = null,
        $method = HttpRequest::METHOD_POST,
        array $extraParams = null,
        $realm = null
    ) {
        $params                    = $this->assembleParameters($token, $extraParams);
        $params['oauth_signature'] = $this->generateSignature($uri, $params, $token, $method);

        ksort($params);

        $parts = array();
        foreach ($params as $key => $value) {
            if (!preg_match(static::AUTH_HEADER_REGEX, $key)) {
                continue;
            }
            $parts[] = sprintf('%s="%s"', $key, $value);
        }

        $realm = sprintf('realm="%s", ', $realm ? $realm : '');

        return 'OAuth ' . $realm . implode(', ', $parts);
    }

    /**
     * @param string $uri
     * @param array $params
     * @param TokenInterface|null $token
     * @param string $method
     * @return string
     */
    public function generateSignature(
        $uri,
        array $params,
        TokenInterface $token = null,
        $method = HttpRequest::METHOD_GET
    ) {
        $signer = $this->getSigner($this->getOptions()->getSignatureMethod());
        $data   = $this->getSignatureString($uri, $params, $method);
        $key    = $this->getSigningKey($token);

        return rawurlencode($signer->sign($data, $key));
    }

    /**
     * Generates the signing key.
     *
     * @param TokenInterface $token
     * @return string
     */
    public function getSigningKey(TokenInterface $token = null)
    {
        $this->assertRequiredOptions(array('client_secret'));

        return $this->getOptions()->getClientSecret() . '&' . ($token ? $token->getSecret() : '');
    }

    /**
     * Builds the base signature string from a uri, array of parameters, and a request method.
     *
     * @param string $uri
     * @param array $params
     * @param string $method
     * @return string
     */
    public function getSignatureString($uri, array $params, $method = HttpRequest::METHOD_POST)
    {
        return implode('&', array($method, rawurlencode($uri), rawurlencode($this->getParameterString($params))));
    }

    /**
     * @return ClientOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param boolean $debugMode
     * @return Client
     */
    public function setDebugMode($debugMode)
    {
        $this->debugMode = $debugMode;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getDebugMode()
    {
        return $this->debugMode;
    }

    /**
     * @param string $debugNonce
     * @return Client
     */
    public function setDebugNonce($debugNonce)
    {
        $this->debugNonce = $debugNonce;
        return $this;
    }

    /**
     * @return string
     */
    public function getDebugNonce()
    {
        return $this->debugNonce;
    }

    /**
     * @param int $debugTimestamp
     * @return Client
     */
    public function setDebugTimestamp($debugTimestamp)
    {
        $this->debugTimestamp = $debugTimestamp;
        return $this;
    }

    /**
     * @return int
     */
    public function getDebugTimestamp()
    {
        return $this->debugTimestamp;
    }

    /**
     * Builds an encoded parameter string from an array of parameters.
     *
     * @param array $params
     * @return string
     */
    public function getParameterString(array $params)
    {
        ksort($params);

        return $this->httpBuildQuery3986($params);
    }

    /**
     * @param string $method
     * @throws Exception\InvalidSignatureMethod
     * @return \SpiffyConnect\Client\Oauth1\Signature\SignatureInterface
     */
    protected function getSigner($method)
    {
        if (!isset($this->signatureClassmap[$method])) {
            throw new Exception\InvalidSignatureMethod($method);
        }

        $class = $this->signatureClassmap[$method];
        return new $class();
    }

    /**
     * Prepares an HttpClient for an OAuth transaction.
     *
     * @param string $uri
     * @param string $method
     * @param array|null $params
     * @param TokenInterface|null $token
     * @return HttpClient
     */
    protected function prepareClient(
        $uri,
        $method = HttpRequest::METHOD_POST,
        array $params = null,
        TokenInterface $token = null
    ) {
        $client = parent::prepareHttpClient($uri, $method, $params);

        $this->setRequestAuthorizationHeader($client->getRequest(), $token);
        return $client;
    }

    /**
     * Generate a nonce.
     *
     * @return string
     */
    protected function generateNonce()
    {
        if ($this->debugMode && $this->debugNonce) {
            return $this->debugNonce;
        }
        return md5(microtime(true) . mt_rand());
    }

    /**
     * Assembles parameters based on basic oauth parameters and any additionally supplied parameters.
     *
     * @param TokenInterface $token
     * @param array $extra
     * @return array
     */
    protected function assembleParameters(TokenInterface $token = null, $extra = null)
    {
        $this->assertRequiredOptions(array(
            'client_id',
            'signature_method',
            'version'
        ));

        $params = array(
            'oauth_consumer_key'     => $this->getOptions()->getClientId(),
            'oauth_nonce'            => $this->generateNonce(),
            'oauth_signature_method' => $this->getOptions()->getSignatureMethod(),
            'oauth_timestamp'        => $this->debugMode && $this->debugTimestamp ? $this->debugTimestamp : time(),
            'oauth_version'          => $this->getOptions()->getVersion()
        );

        if ($token) {
            $params['oauth_token'] = $token->getToken();
        } else {
            $params['oauth_callback'] = $this->getOptions()->getRedirectUri();
        }

        if (is_array($extra)) {
            $params = array_merge($extra, $params);
        }

        ksort($params);

        return $params;
    }
}