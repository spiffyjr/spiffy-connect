<?php

namespace SpiffyConnectTest\Client\Oauth1;

use SpiffyConnect\Client\Oauth1\Client;
use SpiffyConnect\Client\Oauth1\Token\AccessToken;
use SpiffyConnect\Client\Oauth1\Token\RequestToken;
use Zend\Http\Client\Adapter\Test as TestAdapter;
use Zend\Http\Request;
use Zend\Http\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::getRequestToken
     */
    public function testRequestTokenExceptionOnInvalidResponse()
    {
        $this->setExpectedException('SpiffyConnect\Client\Exception\InvalidResponseException');

        $client = $this->getTestClient('empty');
        $client->getRequestToken('http://www.foo.com/oauth/request');
    }

    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::getRequestToken
     */
    public function testRequestToken()
    {
        $client = $this->getTestClient('request');
        $client->getRequestToken('http://www.foo.com/oauth/request');

        $this->setExpectedException('SpiffyConnect\Client\Exception\InvalidResponseException', 'oauth_callback_confirmed was not true');
        $client = $this->getTestClient('invalid_request');
        $client->getRequestToken('http://www.foo.com/oauth/request');
    }

    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::getRedirectResponse
     */
    public function testRedirectResponse()
    {
        $client = new Client();
        $token  = new RequestToken();
        $token->setToken('token');

        $response = $client->getRedirectResponse('http://www.foo.com', $token);
        $header   = $response->getHeaders()->get('Location')->toString();

        $this->assertEquals('Location: http://www.foo.com/?oauth_token=token', $header);
        $this->assertEquals(302, $response->getStatusCode());
    }

    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::setRequestAuthorizationHeader
     */
    public function testSetRequestAuthorizationHeader()
    {
        $request = new Request();
        $request->setMethod('GET');
        $request->setUri('http://www.foo.bar/');
        $request->getQuery()->fromArray(array('foo' => 'bar', 'awesome' => 'sauce'));

        $client = new Client(array(
            'client_id'        => 'foo',
            'client_secret'    => 'bar',
            'signature_method' => 'HMAC-SHA1'
        ));
        $client->setDebugMode(true)
            ->setDebugNonce('nonce')
            ->setDebugTimestamp(123456789);

        $token = new AccessToken();
        $token->setToken('token')
              ->setSecret('secret');

        $client->setRequestAuthorizationHeader($request, $token);

        $expected = 'Authorization: OAuth realm="", oauth_consumer_key="foo", oauth_nonce="nonce", oauth_signature="5PTEwePISdIPuDqS3mFF4wg3dZY%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="123456789", oauth_token="token", oauth_version="1.0"';

        $this->assertEquals($expected, $request->getHeader('Authorization')->toString());
    }

    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::generateAuthorizationHeader
     */
    public function testAuthorizationHeader()
    {
        $client = new Client(array(
            'client_id'        => 'foo',
            'client_secret'    => 'bar',
            'signature_method' => 'HMAC-SHA1'
        ));
        $client->setDebugMode(true)
            ->setDebugNonce('nonce')
            ->setDebugTimestamp(123456789);

        $token = new AccessToken();
        $token->setToken('token')
              ->setSecret('secret');

        $header = $client->generateAuthorizationHeader(
            'http://www.foo.bar/',
            $token,
            'GET',
            array(
                'foo'     => 'bar',
                'awesome' => 'sauce'
            )
        );

        $expected = 'OAuth realm="", oauth_consumer_key="foo", oauth_nonce="nonce", oauth_signature="5PTEwePISdIPuDqS3mFF4wg3dZY%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="123456789", oauth_token="token", oauth_version="1.0"';
        $this->assertEquals($expected, $header);
    }

    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::getParameterString
     */
    public function testParameterString()
    {
        $client = new Client();
        $params = array(
            'd'         => 'this+is+a\nice value',
            'a'         => 'some value',
            'something' => 'value',
            'foo'       => 'bar'
        );

        $result   = $client->getParameterString($params);
        $expected = 'a=some%20value&d=this%2Bis%2Ba%5Cnice%20value&foo=bar&something=value';

        $this->assertEquals($expected, $result);
    }

    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::getSignatureString
     */
    public function testSignatureString()
    {
        $client = new Client();
        $method = 'POST';
        $uri    = 'http://www.foo.bar';
        $params = array(
            'd'         => 'this+is+a\nice value',
            'a'         => 'some value',
            'something' => 'value',
            'foo'       => 'bar'
        );

        $result   = $client->getSignatureString($uri, $params, $method);
        $expected = 'POST&http%3A%2F%2Fwww.foo.bar&a%3Dsome%2520value%26d%3Dthis%252Bis%252Ba%255Cnice%2520value%26foo%3Dbar%26something%3Dvalue';

        $this->assertEquals($result, $expected);
    }

    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::getSigningKey
     */
    public function testSigningKey()
    {
        $client       = new Client(array('client_secret' => 'foobar'));
        $requestToken = new RequestToken();
        $requestToken->setSecret('request');

        $accessToken  = new AccessToken();
        $accessToken->setSecret('access');

        $this->assertEquals('foobar&', $client->getSigningKey());
        $this->assertEquals('foobar&request', $client->getSigningKey($requestToken));
        $this->assertEquals('foobar&access', $client->getSigningKey($accessToken));

        $client = new Client();
        $this->setExpectedException('SpiffyConnect\Client\Exception\MissingOptionException');
        $client->getSigningKey();
    }

    /**
     * @covers \SpiffyConnect\Client\Oauth1\Client::generateSignature
     */
    public function testHmacSignatureGenerator()
    {
        $client = new Client(array(
            'client_id'        => 'foo',
            'client_secret'    => 'bar',
            'signature_method' => 'HMAC-SHA1'
        ));

        $token = new AccessToken();
        $token->setToken('token')
              ->setSecret('secret');

        $signature = $client->generateSignature(
            'http://www.foo.bar',
            array(
                'foo'     => 'bar',
                'awesome' => 'sauce'
            ),
            $token,
            'POST'
        );

        $this->assertEquals('dmROUZYeum0JDN81Ovsw3liJIw0%3D', $signature);
    }

    protected function getTestClient($mode)
    {
        $adapter = new TestAdapter();
        $client  = new Client(array(
            'client_id'     => 'id',
            'client_secret' => 'secret',
        ));
        $client->getHttpClient()->setAdapter($adapter);

        switch ($mode) {
            case 'empty':
                $response = new Response();
                $response->setStatusCode(200);
                $adapter->setResponse($response);
                break;

            case '404':
                $response = new Response();
                $response->setStatusCode(404);
                $adapter->setResponse($response);
                break;

            case 'request':
                $response = new Response();
                $response->setStatusCode(200);
                $response->setContent('oauth_token=token&oauth_token_secret=secret&oauth_callback_confirmed=true');
                $adapter->setResponse($response);
                break;

            case 'invalid_request':
                $response = new Response();
                $response->setStatusCode(200);
                $response->setContent('oauth_token=token&oauth_token_secret=secret');
                $adapter->setResponse($response);
                break;
        }

        return $client;
    }
}