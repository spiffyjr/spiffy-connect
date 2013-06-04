<?php

namespace SpiffyConnect\Provider;

interface ProfileProviderInterface
{
    /**
     * @return \SpiffyConnect\Profile\ProfileInterface
     */
    public function getUserProfile();
}