<?php

namespace SpiffyConnect\Client;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request as HttpRequest;
use Zend\Http\Response as HttpResponse;
use Zend\Http\PhpEnvironment\Response as PhpResponse;
use Zend\Json\Json;
use Zend\Stdlib\Response;

abstract class AbstractClient implements ClientInterface
{
    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @var AbstractOptions
     */
    protected $options;

    /**
     * @param string $baseUri
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @return string
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param AbstractOptions $options
     * @return AbstractClient
     */
    public function setOptions(AbstractOptions $options)
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
        if (!$this->httpClient instanceof HttpClient) {
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
     * PHP 5.3.3 version of http_build_query with enctype set to PHP_QUERY_RFC3986.
     *
     * @param array $params
     * @param string $sep
     * @return string
     */
    public function httpBuildQuery3986(array $params, $sep = '&')
    {
        $parts = array();
        foreach ($params as $key => $value) {
            $parts[] = sprintf('%s=%s', $key, rawurlencode($value));
        }

        return implode($sep, $parts);
    }

    /**
     * Force a redirect and exit.
     *
     * @param Response $response
     */
    public function redirect(Response $response)
    {
        if (!$response instanceof PhpResponse) {
            $string   = $response->toString();
            $response = new PhpResponse();
            $response->fromString($string);
        }

        $response->send();
        exit;
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
                    get_class($this)
                ));
            }

            if (null === $this->getOptions()->{$getter}()) {
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
        $client->getRequest()->getHeaders()->addHeaderLine('Accept: */*');

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