<?php

namespace SpiffyConnect\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $httpAdapter = array();

    /**
     * @var array
     */
    protected $providers =  array();

    /**
     * @var string
     */
    protected $endpointUri;

    /**
     * @param array $httpAdapter
     * @return ModuleOptions
     */
    public function setHttpAdapter($httpAdapter)
    {
        $this->httpAdapter = $httpAdapter;
        return $this;
    }

    /**
     * @return array
     */
    public function getHttpAdapter()
    {
        return $this->httpAdapter;
    }

    /**
     * @param array $providers
     * @return ModuleOptions
     */
    public function setProviders($providers)
    {
        $this->providers = $providers;
        return $this;
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * @param string $endpointUri
     * @return ModuleOptions
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
}