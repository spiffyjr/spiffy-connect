<?php

namespace SpiffyConnect\Provider\OAuth2;

use SpiffyConnect\Profile\Profile;
use SpiffyConnect\Profile\ProfileInterface;
use SpiffyConnect\Provider\ProfileProviderInterface;
use Zend\Json\Json;

class GoogleProvider extends AbstractProvider implements ProfileProviderInterface
{
    /**
     * @var string
     */
    protected $name = 'google';

    /**
     * @var string
     */
    protected $profileUri = 'https://www.googleapis.com/oauth2/v3/userinfo';

    public function __construct(array $options = array())
    {
        $provider = array(
            'access_token_uri'       => 'https://accounts.google.com/o/oauth2/token',
            'authorization_code_uri' => 'https://accounts.google.com/o/oauth2/auth',
            'scope'                  => 'profile+email',

            'access_token_parameters' => array(
                'grant_type' => 'authorization_code',
            ),
            'authorization_code_parameters' => array(
                'response_type' => 'code'
            ),
        );

        $this->options = new ProviderOptions(array_merge($provider, $options));
    }

    /**
     * @return \SpiffyConnect\Profile\ProfileInterface
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
            'id'           => $content->sub,
            'email'        => $content->email,
            'display_name' => $content->name,
            'raw_response' => $response
        ));

        $storage->write($profile);

        return $profile;
    }
}