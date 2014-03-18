<?php

namespace SpiffyConnectTest\Client\OAuth1\Signature;

use SpiffyConnect\Client\OAuth1\Signature\HmacSha1Signature;

class HmacSha1SignatureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \SpiffyConnect\Client\OAuth1\Signature\HmacSha1Signature::sign
     */
    public function testSign()
    {
        $signature = new HmacSha1Signature();
        $this->assertEquals('hdFVxV7ShqMAvRzxJN4I2H6RTzo=', $signature->sign('foo', 'bar'));
    }
}