<?php

namespace SpiffyConnect\Provider;

interface ProviderOptionsInterface
{
    /**
     * @param string $redirectUri
     * @return ProviderOptionsInterface
     */
    public function setRedirectUri($redirectUri);

    /**
     * @return string
     */
    public function getRedirectUri();
}