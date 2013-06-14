<?php

namespace SpiffyConnect\Client\Oauth2;

use SpiffyConnect\Client\AbstractClient;
use SpiffyConnect\Client\Oauth2\AccessToken;
use SpiffyConnect\Client\Exception;
use Zend\Http\Client as HttpClient;
use Zend\Http\PhpEnvironment\Request as PhpRequest;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response;
use Zend\Uri\Uri;

class Client extends AbstractClient
{
    /**
     * @param array|null|\Traversable $options
     */
    public function __construct($options = null)
    {
        $this->options = new ClientOptions($options);
    }

    /**
     * @param string $uri
     * @param AccessToken $token
     * @param string $method
     * @param array|null $params
     * @return Response
     * @throws Exception\InvalidAccessTokenException
     */
    public function request($uri, AccessToken $token, $method = HttpRequest::METHOD_GET, array $params = null)
    {
        if (!$token->isValid()) {
            throw new Exception\InvalidAccessTokenException();
        }

        return $this->prepareHttpClient($uri, $method, $params, $token)->send();
    }

    /**
     * @param string $uri
     * @param AuthorizationCode $code
     * @param string $method
     * @param array|null $params
     * @return AccessToken
     */
    public function getAccessToken($uri, AuthorizationCode $code, $method = HttpRequest::METHOD_POST, array $params = null)
    {
        $this->assertRequiredOptions(array('client_id', 'client_secret', 'redirect_uri'));

        $options = $this->getOptions();

        $params['code']          = $code->getCode();
        $params['client_id']     = $options->getClientId();
        $params['client_secret'] = $options->getClientSecret();
        $params['redirect_uri']  = $options->getRedirectUri();

        $client = $this->getHttpClient();
        $client->setMethod($method);
        $client->setUri($uri);
        $client->setParameterPost($params);

        return $this->createAccessToken($client->send());
    }

    /**
     * Get the authorization code if it's available.
     *
     * @return null|AuthorizationCode
     */
    public function getAuthorizationCode()
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

        $options = $this->getOptions();

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
     * Creates an access token.
     *
     * @param Response $response
     * @throws Exception\InvalidAccessTokenException
     * @return AccessToken
     */
    protected function createAccessToken(Response $response)
    {
        $options    = $this->getOptions();
        $content    = $this->decodeResponse($response, $options->getResponseFormat());
        $expiresKey = $options->getExpireTimeKey();
        $accessKey  = $options->getAccessTokenKey();

        if (!isset($content[$accessKey])) {
            throw new Exception\InvalidAccessTokenException('missing access token from response');
        }

        return new AccessToken(array(
            'access_token' => $content[$accessKey],
            'expire_time'  => isset($content[$expiresKey]) ? $content[$expiresKey] : null,
        ));
    }

    /**
     * Prepares an HttpClient for an OAuth transaction.
     *
     * @param string $uri
     * @param string $method
     * @param array|null $params
     * @param AccessToken|null $token
     * @return HttpClient
     */
    protected function prepareHttpClient(
        $uri,
        $method = HttpRequest::METHOD_POST,
        array $params = null,
        AccessToken $token = null
    ) {
        $params['access_token'] = $token->getAccessToken();
        return parent::prepareHttpClient($uri, $method, $params);
    }
}