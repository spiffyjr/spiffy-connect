<?php

namespace SpiffyConnect\Client\Oauth1\Signature;

interface SignatureInterface
{
    /**
     * @param string $data
     * @param string $key
     * @return string
     */
    public function sign($data, $key);
}