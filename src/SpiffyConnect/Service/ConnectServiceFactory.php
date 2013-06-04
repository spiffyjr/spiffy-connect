<?php

namespace SpiffyConnect\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConnectServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \SpiffyConnect\Options\ModuleOptions $config */
        $options = $serviceLocator->get('SpiffyConnect\Options\ModuleOptions');
        $service = new ConnectService();

        foreach ($options->getProviders() as $provider) {
            $service->addProvider($provider);
        }

        return $service;
    }
}