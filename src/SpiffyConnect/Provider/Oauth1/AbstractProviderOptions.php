<?php

namespace SpiffyConnect\Provider\OAuth1;

use SpiffyConnect\Client\OAuth1\ClientOptions;

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