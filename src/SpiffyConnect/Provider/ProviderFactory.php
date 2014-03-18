<?php

namespace SpiffyConnect\Provider;

class ProviderFactory
{
    /**
     * @var array
     */
    protected $providerClassmap = array(
        'github'   => 'SpiffyConnect\Provider\OAuth2\GithubProvider',
        'google'   => 'SpiffyConnect\Provider\OAuth2\GoogleProvider',
        'facebook' => 'SpiffyConnect\Provider\OAuth2\FacebookProvider',
        'twitter'  => 'SpiffyConnect\Provider\OAuth1\TwitterProvider'
    );

    /**
     * @param array $spec
     * @return ProviderInterface
     * @throws Exception\InvalidArgumentException on invalid spec
     * @throws Exception\InvalidProviderException on provider that does not implement ProviderInterface
     */
    public function createProvider(array $spec)
    {
        if (!isset($spec['name'])) {
            throw new Exception\InvalidArgumentException('Provider name is required.');
        }

        if (!isset($spec['options'])) {
            throw new Exception\InvalidArgumentException('Provider options must be specified.');
        }

        $class = isset($spec['class']) ? $spec['class'] : null;
        if (isset($spec['name']) && !$class) {
            if (!isset($this->providerClassmap[$spec['name']])) {
                throw new Exception\InvalidArgumentException(sprintf(
                    'No provider with name "%s" is registered. Trying using "class" with the FQCN instead.',
                    $spec['name']
                ));
            }

            $class = $this->providerClassmap[$spec['name']];
        }

        if (!class_exists($class)) {
            throw new Exception\InvalidArgumentException(sprintf(
               'Failed to find provider with class name "%s"',
                $class
            ));
        }

        /** @var \SpiffyConnect\Provider\ProviderInterface $provider */
        $provider = new $class;

        if (!$provider instanceof ProviderInterface) {
            throw new Exception\InvalidProviderException();
        }

        $provider->setName($spec['name']);
        $provider->getOptions()->setFromArray($spec['options']);

        return $provider;
    }
}