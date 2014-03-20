<?php

namespace SpiffyConnect\Client\OAuth1\Token;

interface  TokenInterface
{
    /**
     * @return string
     */
    public function getToken();

    /**
     * @return string
     */
    public function getSecret();
}