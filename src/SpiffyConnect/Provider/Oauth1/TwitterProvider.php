<?php

namespace SpiffyConnect\Provider\Oauth1;

use SpiffyConnect\Storage\SessionStorage;
use Zend\Http\Client as HttpClient;
use Zend\Http\PhpEnvironment\Request;

class TwitterProvider extends AbstractProvider
{
    /**
     * @var string
     */
    protected $name = 'twitter';

    public function __construct($options = null)
    {
        $this->options = new TwitterOptions($options);
    }
}