<?php

namespace SpiffyConnect\Client\OAuth2;

use SpiffyConnect\Client\AbstractClient;
use SpiffyConnect\Client\Exception;
use Zend\Http\Client as HttpClient;
use Zend\Http\PhpEnvironment\Request as PhpRequest;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response;
use Zend\Uri\Uri;

class Client extends AbstractClient
{
    /**
     * @var AccessToken
     */
    protected $accessToken;

    /**
     * @param array|null|\Traversable $options
     */
    public function __construct($options = null)
    {
        $this->options = new ClientOptions($options);
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array|null $params
     * @return Response
     * @throws Exception\InvalidAccessTokenException
     */
    public function request($uri, array $params = null, $method = HttpRequest::METHOD_GET)
    {
        $client = $this->prepareHttpClient($uri, $method, $params);

        return $this->decodeResponse($client->send());
    }

    /**
     * @param string $method
     * @param array|null $params
     * @return AccessToken
     */
    public function requestToken(array $params = null, $method = HttpRequest::METHOD_POST)
    {
        $this->assertRequiredOptions(array('client_id', 'client_secret'));

        $params['client_id']     = $this->options->getClientId();
        $params['client_secret'] = $this->options->getClientSecret();

        $client = $this->getHttpClient();
        $client->setMethod($method);
        $client->setUri($this->options->getBaseUri() . '/' . $this->options->getOAuthEndpoint());
        $client->setParameterPost($params);

        return $this->createAccessToken($client->send());
    }

    /**
     * @throws Exception\InvalidAccessTokenException
     */
    public function requestTokenRefresh()
    {
        $token = $this->getAccessToken();

        if (!$token->getRefreshToken()) {
            throw new Exception\InvalidAccessTokenException();
        }

        $refreshToken = $token->getRefreshToken();

        $token = $this->requestToken(['grant_type' => 'refresh_token', 'refresh_token' => $token->getRefreshToken()]);
        $token->setRefreshToken($refreshToken);
        $this->setAccessToken($token);

        return $token;
    }

    /**
     * Get the authorization code if it's available.
     *
     * @return null|AuthorizationCode
     */
    public function requestAuthorizationCode()
    {
        $request = new PhpRequest();
        $query   = $request->getQuery();
        if ($query->get('code')) {
            return new AuthorizationCode(array(
                'code' => $query->get('code')
            ));
        }

        return null;
    }

    /**
     * Get a redirect response for authorization.
     *
     * @param string $uri
     * @param array $params
     * @return Response
     */
    public function getRedirectResponse($uri, array $params = null)
    {
        $this->assertRequiredOptions(array('client_id', 'redirect_uri', 'scope'));

        $options = $this->options;

        $params['client_id']    = $options->getClientId();
        $params['redirect_uri'] = $options->getRedirectUri();
        $params['scope']        = $options->getScope();

        // + (not %2B) is valid and is required for some providers
        $uri = new Uri($uri);
        $uri->setQuery($params);
        $uri = str_replace('%2B', '+', $uri->toString());

        $response = new Response();
        $response->getHeaders()->addHeaderLine('Location', $uri);
        $response->setStatusCode(302);

        return $response;
    }

    /**
     * @param AccessToken $accessToken
     */
    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * @return AccessToken
     */
    public function getAccessToken()
    {
        if (!$this->accessToken instanceof AccessToken) {
            $this->accessToken = new AccessToken();
        }
        return $this->accessToken;
    }

    /**
     * Creates an access token.
     *
     * @param Response $response
     * @throws Exception\InvalidAccessTokenException
     * @return AccessToken
     */
    protected function createAccessToken(Response $response)
    {
        $options    = $this->options;
        $content    = $this->decodeResponse($response, $options->getFormat());
        $expiresKey = $options->getExpireTimeKey();
        $accessKey  = $options->getAccessTokenKey();
        $refreshKey = $options->getRefreshTokenKey();

        if (!isset($content[$accessKey])) {
            throw new Exception\InvalidAccessTokenException('missing access token from response');
        }

        return new AccessToken(array(
            'access_token' => $content[$accessKey],
            'expires_in'  => isset($content[$expiresKey]) ? $content[$expiresKey] : null,
            'refresh_token' => isset($content[$refreshKey]) ? $content[$refreshKey] : null,
        ));
    }

    /**
     * Prepares an HttpClient for an OAuth transaction.
     *
     * @param string $uri
     * @param string $method
     * @param array|null $params
     * @throws Exception\InvalidAccessTokenException
     * @return HttpClient
     */
    protected function prepareHttpClient(
        $uri,
        $method = HttpRequest::METHOD_POST,
        array $params = null
    ) {
        $token = $this->getAccessToken();
        if (!$token->isValid()) {
            $token = $this->requestTokenRefresh();
        }

        $client = parent::prepareHttpClient($this->options->getBaseUri() . '/' . $uri, $method, $params);
        $headers = $client->getRequest()->getHeaders();
        $headers->addHeaderLine(sprintf('Authorization: Bearer %s', $token->getAccessToken()));
        $headers->addHeaderLine('Content-type: application/json');
        $headers->addHeaderLine('Accept: application/json');

        return $client;
    }
}