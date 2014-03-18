<?php

namespace SpiffyConnect\Client\OAuth1\Signature;

use Zend\Crypt\Hmac;

class HmacSha1Signature implements SignatureInterface
{
    /**
     * @param string $data
     * @param string $key
     * @return string
     */
    public function sign($data, $key)
    {
        return base64_encode(Hmac::compute($key, 'sha1', $data, Hmac::OUTPUT_BINARY));
    }
}