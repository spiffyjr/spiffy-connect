<?php

return array(
    'spiffy_connect' => array(
        'http_adapter' => array(
            'class' => 'Zend\Http\Client\Adapter\Curl',
            'options' => array()
        ),

        'providers' => array(
            /* Sample configuration for Google provider
            array(
                'name' => 'google',
                'options' => array(
                    'client_id'     => 'id',
                    'client_secret' => 'secret',
                )
            )
            */
        )
    )
);