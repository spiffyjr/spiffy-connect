<?php

namespace SpiffyConnect\Service;

use SpiffyConnect\Options\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OptionsModuleOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Configuration');

        return new ModuleOptions($config['spiffy_connect']);
    }
}