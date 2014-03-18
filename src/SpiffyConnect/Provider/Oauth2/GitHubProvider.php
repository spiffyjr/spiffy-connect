<?php

namespace SpiffyConnect\Provider\OAuth2;

use SpiffyConnect\Profile\Profile;
use SpiffyConnect\Profile\ProfileInterface;
use SpiffyConnect\Provider\ProfileProviderInterface;
use Zend\Json\Json;

class GitHubProvider extends AbstractProvider implements ProfileProviderInterface
{
    /**
     * @var string
     */
    protected $name = 'github';

    /**
     * @var string
     */
    protected $profileUri = 'https://api.github.com/user';

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


    /**
     * {@inheritDoc}
     */
    public function getUserProfile()
    {
        $storage = $this->getStorage('profile');
        $profile = $storage->read();

        if ($profile instanceof ProfileInterface) {
            return $profile;
        }

        $response = $this->request($this->profileUri);
        $content  = Json::decode($response->getBody());

        $profile = new Profile(array(
            'id'           => $content->id,
            'email'        => $content->email,
            'display_name' => $content->name,
            'raw_response' => $response
        ));

        $storage->write($profile);

        return $profile;
    }
}