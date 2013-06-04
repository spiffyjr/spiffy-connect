<?php

namespace SpiffyConnect\Client;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Json\Json;
use Zend\Stdlib\AbstractOptions;

abstract class AbstractClient implements ClientInterface
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var AbstractOptions
     */
    protected $options;

    /**
     * @param AbstractOptions $options
     * @return AbstractClient
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return AbstractOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param \Zend\Http\Client $httpClient
     * @return HttpClient
     */
    public function setHttpClient(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        if (!$this->httpClient) {
            $this->httpClient = new HttpClient();
        }
        return $this->httpClient;
    }

    /**
     * Parses a query string into an array.
     *
     * @param string $query
     * @return array
     */
    public function parseQueryString($query)
    {
        $params = array();
        if (empty($query)) {
            return array();
        }

        $parts = explode('&', $query);
        foreach ($parts as $pair) {
            list($key, $value) = explode('=', $pair);
            $params[rawurldecode($key)] = rawurldecode($value);
        }
        return $params;
    }

    /**
     * Throws an exception if one of the required options is not present.
     *
     * @param array $options
     * @throws Exception\MissingOptionException
     * @throws Exception\RuntimeException
     */
    protected function assertRequiredOptions(array $options)
    {
        foreach ($options as $option) {
            $getter = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $option)));
            if (!method_exists($this->getOPtions(), $getter)) {
                throw new Exception\RuntimeException(sprintf(
                    'attempting to call getter "%s" on "%s" which does not exist',
                    $getter,
                    get_class($th)
                ));
            }

            if (!$this->getOptions()->{$getter}()) {
                throw new Exception\MissingOptionException($option);
            }
        }
    }

    /**
     * @param HttpResponse $response
     * @param string $format
     * @throws Exception\InvalidResponseFormatException
     * @throws Exception\ResponseErrorException
     * @return mixed
     */
    protected function decodeResponse(HttpResponse $response, $format = 'json')
    {
        $content = $response->getBody();
        $status  = $response->getStatusCode();

        if ($content) {
            switch (strtolower($format)) {
                case 'json':
                    $content = Json::decode($content, true);
                    break;
                case 'querystring':
                    parse_str($content, $content);
                    break;
                default:
                    throw new Exception\InvalidResponseFormatException($format);
                    break;
            }
        }

        if (HttpResponse::STATUS_CODE_200 !== $status) {
            throw new Exception\ResponseErrorException(var_export($content, true), $status);
        }

        return $content;
    }

    /**
     * Prepares the HTTP client.
     *
     * @param $uri
     * @param string $method
     * @param array $params
     * @return HttpClient
     */
    protected function prepareHttpClient(
        $uri,
        $method = HttpRequest::METHOD_POST,
        array $params = null
    ) {
        $client = $this->getHttpClient();
        $client->reset();
        $client->setUri($uri);
        $client->setMethod($method);

        if (is_array($params)) {
            switch($method) {
                case HttpRequest::METHOD_GET:
                    $client->setParameterGet($params);
                    break;
                case HttpRequest::METHOD_POST:
                    $client->setParameterPost($params);
                    break;
            }
        }

        return $client;
    }
}