<?php

namespace SpiffyConnect\Client;

use Zend\Stdlib\AbstractOptions as BaseAbstractOptions;

abstract class AbstractOptions extends BaseAbstractOptions
{
    /**
     * @var string
     */
    protected $format = 'json';

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

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
}
