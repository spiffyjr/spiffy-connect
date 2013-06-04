<?php

namespace SpiffyConnect\Client;

use Zend\Http\Client as HttpClient;
use Zend\Stdlib\AbstractOptions;

interface ClientInterface
{
    /**
     * @param AbstractOptions $options
     * @return AbstractClient
     */
    public function setOptions($options);

    /**
     * @return AbstractOptions
     */
    public function getOptions();

    /**
     * @param \Zend\Http\Client $httpClient
     * @return HttpClient
     */
    public function setHttpClient(HttpClient $httpClient);

    /**
     * @return HttpClient
     */
    public function getHttpClient();

    /**
     * Parses a query string into an array.
     *
     * @param string $query
     * @return array
     */
    public function parseQueryString($query);
}