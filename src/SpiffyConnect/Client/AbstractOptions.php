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
}
