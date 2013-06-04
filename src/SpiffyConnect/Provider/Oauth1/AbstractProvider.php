<?php

namespace SpiffyConnect\Provider\Oauth1;

use SpiffyConnect\Client\Oauth1\Client;
use SpiffyConnect\Client\Oauth1\Token\AccessToken;
use SpiffyConnect\Client\Oauth1\Token\RequestToken;
use SpiffyConnect\Provider\ProviderInterface;
use SpiffyConnect\Storage\SessionStorage;
use SpiffyConnect\Storage\StorageInterface;
use Zend\Http\Client as HttpClient;
use Zend\Http\Response as HttpResponse;
use Zend\Http\PhpEnvironment\Response as PhpResponse;
use Zend\Http\Request as HttpRequest;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var AbstractProviderOptions
     */
    protected $options;

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @param string $uri
     * @param string $method
     * @param null|array $params
     * @return \Zend\Http\Response
     */
    public function request($uri, $method = HttpRequest::METHOD_GET, array $params = null)
    {
        return $this->getClient()->request($this->getAccessToken(), $uri, $method, $params);
    }

    /**
     * Performs full authentication for the provider.
     *
     * @return AbstractProvider
     */
    public function authenticate()
    {
        $this->getAccessToken();
        return $this;
    }

    /**
     * Redirect to the authentication url for the provider.
     *
     * @param RequestToken $token
     * @return void
     */
    public function redirect(RequestToken $token)
    {
        $response = $this->getClient()->getRedirectResponse(
            $this->getOptions()->getAuthenticateUri(),
            $token
        );

        if ($response instanceof HttpResponse) {
            $response = PhpResponse::fromString($response->toString());
        } else if (!$response instanceof PhpResponse) {
            // todo: throw exception
            exit;
        }

        $response->send();
        exit;
    }

    /**
     * Uses the $redirect parameter to determine whether or not
     * redirect should be automatic or a Zend\Http\Response
     * should be returned and handled manually.
     *
     * @param bool $redirect
     * @param string $method
     * @return RequestToken|\Zend\Http\Response
     */
    public function getRequestToken($redirect = true, $method = HttpRequest::METHOD_POST)
    {
        $storage = $this->getStorage();
        $token   = $storage->read();

        if ($token instanceof RequestToken) {
            return $token;
        }

        $client = $this->getClient();
        $token  = $client->getRequestToken(
            $this->getOptions()->getRequestTokenUri(),
            $method,
            $this->getOptions()->getRedirectUri()
        );

        $storage->write($token);

        if ($redirect) {
            $this->redirect($token);
        }

        return $client->getRedirectResponse(
            $this->getOptions()->getAuthenticateUri(),
            $token
        );
    }

    /**
     * Gets an access token. If no request token has been retrieved
     * will retrieve a request token first. Uses the $redirect parameter
     * to determine whether or not redirect should be automatic or a Zend\Http\Response
     * should be returned and handled manually.
     *
     * @param bool $redirect
     * @return AccessToken|\Zend\Http\Response
     */
    public function getAccessToken($redirect = true)
    {
        $storage = $this->getStorage();
        $token   = $storage->read();
        $client  = $this->getClient();

        if ($token instanceof AccessToken) {
            return $token;
        } else if (!$token instanceof RequestToken) {
            $token = $this->getRequestToken($redirect);
        }

        $token = $client->getAccessToken(
            $this->getOptions()->getAccessTokenUri(),
            $token
        );

        $storage->write($token);

        return $token;
    }

    /**
     * @param string $name
     * @return AbstractProvider
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param AbstractProviderOptions $options
     * @return AbstractProvider
     */
    public function setOptions(AbstractProviderOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return \SpiffyConnect\Provider\Oauth1\TwitterOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param StorageInterface $storage
     * @return AbstractProvider
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @return StorageInterface
     */
    public function getStorage()
    {
        if (!$this->storage) {
            $this->storage = new SessionStorage(get_called_class(), $this->getName());
        }
        return $this->storage;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client(array(
                'consumer_key'    => $this->getOptions()->getClientId(),
                'consumer_secret' => $this->getOptions()->getClientSecret(),
                'callback_uri'    => $this->getOptions()->getRedirectUri()
            ));
        }
        return $this->client;
    }

    /**
     * @param Client $client
     * @return AbstractProvider
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }
}