<?php

namespace SpiffyConnect\Provider\OAuth2;

use SpiffyConnect\Client\OAuth1\ClientOptions;
use SpiffyConnect\Client\OAuth2\AccessToken;
use SpiffyConnect\Client\OAuth2\AuthorizationCode;
use SpiffyConnect\Client\OAuth2\Client;
use SpiffyConnect\Provider\ProviderInterface;
use SpiffyConnect\Storage\SessionStorage;
use SpiffyConnect\Storage\StorageInterface;
use Zend\Http\PhpEnvironment\Response as PhpResponse;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;

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
     * @var StorageInterface[]
     */
    protected $storage = array();

    /**
     * @var ProviderOptions
     */
    protected $options;

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
     * @param string $uri
     * @param string $method
     * @param null|array $params
     * @return \Zend\Http\Response
     */
    public function request($uri, $method = HttpRequest::METHOD_GET, array $params = null)
    {
        return $this->getClient()->request($uri, $this->getAccessToken(), $method, $params);
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
     * @param string $name
     * @return StorageInterface
     */
    public function getStorage($name = 'default')
    {
        if (!isset($this->storage[$name])) {
            $this->storage[$name] = new SessionStorage(
                __CLASS__ . '_' . $this->getName(),
                $name
            );
        }
        return $this->storage[$name];
    }

    /**
     * @return ProviderOptions
     */
    public function getOptions()
    {
        return $this->options;
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

    /**
     * @return Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Client($this->getOptions()->toArray());
        }
        return $this->client;
    }

    /**
     * Gets an access token. If no authorization code has been retrieved
     * will retrieve an authorization code. Uses the $redirect parameter
     * to determine whether or not redirect should be automatic or a Zend\Http\Response
     * should be returned and handled manually.
     *
     * @param bool $redirect
     * @return AccessToken|\Zend\Http\Response
     */
    public function getAccessToken($redirect = true)
    {
        $storage = $this->getStorage();
        $cache   = $storage->read();

        if ($cache instanceof AccessToken) {
            return $cache;
        } else if (!$cache instanceof AuthorizationCode) {
            $cache = $this->getAuthorizationCode($redirect);
        }

        $token = $this->getClient()->getAccessToken(
            $this->getOptions()->getAccessTokenUri(),
            $cache,
            'POST',
            $this->getOptions()->getAccessTokenParameters()
        );

        $storage->write($token);

        return $token;
    }

    public function getAuthorizationCode($redirect = true)
    {
        $storage = $this->getStorage();
        $code    = $storage->read();

        if ($code instanceof AuthorizationCode) {
            return $code;
        }

        $client = $this->getClient();
        $code   = $client->getAuthorizationCode();

        if (null === $code) {
            if ($redirect) {
                $this->redirect();
            }

            return $client->getRedirectResponse(
                $this->getOptions()->getAuthorizationCodeUri(),
                $this->getOptions()->getAuthorizationCodeParameters()
            );
        }

        $storage->write($code);

        return $code;
    }

    public function redirect()
    {
        $response = $this->getClient()->getRedirectResponse(
            $this->getOptions()->getAuthorizationCodeUri(),
            $this->getOptions()->getAuthorizationCodeParameters()
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
}