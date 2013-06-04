<?php

namespace SpiffyConnect\Provider\Oauth1;

use SpiffyConnect\Client\Oauth1\ClientOptions;

abstract class AbstractProviderOptions extends ClientOptions
{
    /**
     * @return string
     */
    abstract public function getAccessTokenUri();

    /**
     * @return string
     */
    abstract public function getAuthenticateUri();

    /**
     * @return string
     */
    abstract public function getRequestTokenUri();
}