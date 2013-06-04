<?php

namespace SpiffyConnect\Provider;

use Zend\Http\Request as HttpRequest;

interface ProviderInterface
{
    /**
     * @param string $name
     * @return string
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @return \SpiffyConnect\Provider\ProviderOptionsInterface
     */
    public function getOptions();

    /**
     * Performs full authentication for the provider.
     *
     * @return ProviderInterface
     */
    public function authenticate();

    /**
     * @param string $uri
     * @param string $method
     * @param null|array $params
     * @return \Zend\Http\Response
     */
    public function request($uri, $method = HttpRequest::METHOD_GET, array $params = null);
}