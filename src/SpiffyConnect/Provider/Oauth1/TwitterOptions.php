<?php

namespace SpiffyConnect\Provider\OAuth1;

class TwitterOptions extends AbstractProviderOptions
{
    /**
     * @return string
     */
    public function getAccessTokenUri()
    {
        return 'https://api.twitter.com/oauth/access_token';
    }

    /**
     * @return string
     */
    public function getAuthenticateUri()
    {
        return 'https://api.twitter.com/oauth/authenticate';
    }

    /**
     * @return string
     */
    public function getRequestTokenUri()
    {
        return 'https://api.twitter.com/oauth/request_token';
    }
}