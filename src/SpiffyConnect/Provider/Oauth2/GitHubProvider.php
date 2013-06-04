<?php

namespace SpiffyConnect\Provider\Oauth2;

class GitHubProvider extends AbstractProvider
{
    /**
     * @var string
     */
    protected $name = 'github';

    public function __construct(array $options = array())
    {
        $provider = array(
            'access_token_uri'       => 'https://github.com/login/oauth/access_token',
            'authorization_code_uri' => 'https://github.com/login/oauth/authorize',
            'response_format'        => 'querystring',
            'expire_time_key'        => null,
            'scope'                  => 'user,user:email',
        );

        $this->options = new ProviderOptions(array_merge($provider, $options));
    }
}