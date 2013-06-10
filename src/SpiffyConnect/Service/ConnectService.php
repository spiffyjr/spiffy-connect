<?php

namespace SpiffyConnect\Service;

use SpiffyConnect\Client;
use SpiffyConnect\Provider;
use SpiffyConnect\Provider\ProviderFactory;
use SpiffyConnect\Provider\ProviderInterface;

class ConnectService
{
    /**
     * @var Provider\ProviderInterface[]
     */
    protected $providers = array();

    /**
     * @var ProviderFactory
     */
    protected $providerFactory;

    /**
     * @var string
     */
    protected $endpointUri;

    /**
     * @param array|Provider\ProviderInterface $provider
     * @return ConnectService
     */
    public function addProvider($provider)
    {
        if (is_array($provider)) {
            $provider = $this->getProviderFactory()->createProvider($provider);
        }

        if (!$provider instanceof ProviderInterface) {
            // todo: throw new exception
            echo 'invalid provider';
            exit;
        }
        $this->providers[$provider->getName()] = $provider;
        return $this;
    }

    /**
     * @param string $name
     * @return Provider\ProviderInterface
     */
    public function getProvider($name)
    {
        if (!isset($this->providers[$name])) {
            // todo: throw exception
            echo 'no provider';
            exit;
        }
        $provider = $this->providers[$name];
        $provider->getOptions()->setRedirectUri($this->getEndpointUri());

        return $provider;
    }

    /**
     * Get the profile from the provider.
     *
     * @param string $name
     * @return Provider\ProviderInterface
     */
    public function authenticate($name)
    {
        return $this->getProvider($name)->authenticate();
    }

    /**
     * @param string $endpointUri
     * @return ConnectService
     */
    public function setEndpointUri($endpointUri)
    {
        $this->endpointUri = $endpointUri;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndpointUri()
    {
        return $this->endpointUri;
    }

    /**
     * @param \SpiffyConnect\Provider\ProviderFactory $providerFactory
     * @return ConnectService
     */
    public function setProviderFactory($providerFactory)
    {
        $this->providerFactory = $providerFactory;
        return $this;
    }

    /**
     * @return \SpiffyConnect\Provider\ProviderFactory
     */
    public function getProviderFactory()
    {
        if (!$this->providerFactory) {
            $this->providerFactory = new ProviderFactory();
        }
        return $this->providerFactory;
    }
}