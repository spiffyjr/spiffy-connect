<?php

namespace SpiffyConnect\Client\OAuth2;

use Zend\Stdlib\AbstractOptions;

class AuthorizationCode extends AbstractOptions
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @param string $code
     * @return AuthorizationCode
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}