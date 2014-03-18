<?php

namespace SpiffyConnect\Provider\OAuth2;

class FacebookProvider extends AbstractProvider
{
    /**
     * @var string
     */
    protected $name = 'facebook';

    public function __construct(array $options = array())
    {
        $provider = array(
            'access_token_uri'       => 'https://graph.facebook.com/oauth/access_token',
            'authorization_code_uri' => 'https://www.facebook.com/dialog/oauth',
            'response_format'        => 'querystring',
            'expire_time_key'        => 'expires',
            'scope'                  => 'email',
        );

        $this->options = new ProviderOptions(array_merge($provider, $options));
    }
}