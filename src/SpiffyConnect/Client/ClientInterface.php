<?php

namespace SpiffyConnect\Client;

use Zend\Http\Client as HttpClient;

interface ClientInterface
{
    /**
     * @param AbstractOptions $options
     * @return AbstractClient
     */
    public function setOptions(AbstractOptions $options);

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
}