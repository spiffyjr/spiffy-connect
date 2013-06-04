<?php

return array(
    'factories' => array(
        'SpiffyConnect\Options\ModuleOptions'  => 'SpiffyConnect\Service\OptionsModuleOptionsFactory',
        'SpiffyConnect\Service\ConnectService' => 'SpiffyConnect\Service\ConnectServiceFactory',
    ),
);