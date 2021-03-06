<?php

namespace SpiffyConnectTest\Client\OAuth2;

use SpiffyConnect\Client\OAuth2\AuthorizationCode;
use SpiffyConnect\Client\OAuth2\Client;
use Zend\Http\Client\Adapter\Test as TestAdapter;
use Zend\Http\Request;
use Zend\Http\Response;

class ClientTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @cover SpiffyConnect\Client\OAuth2\Client::getAuthorizationCode
     */
    public function testGetAuthorizationCode()
    {
        $client = $this->getTestClient('empty');
        $this->assertEquals(null, $client->getAuthorizationCode());

        $_GET['code'] = 'foobar';
        $client       = $this->getTestClient('empty');
        $code         = $client->getAuthorizationCode();
        $this->assertInstanceOf('SpiffyConnect\Client\OAuth2\AuthorizationCode', $code);
        $this->assertEquals('foobar', $code->getCode());
    }

    /**
     * @cover SpiffyConnect\Client\OAuth2\Client::getAccessToken
     */
    public function testGetAccessToken()
    {
        $code = new AuthorizationCode();
        $code->setCode('foobar');

        $client = $this->getTestClient('access_token');
        $token  = $client->getAccessToken('foo', $code);
        $this->assertInstanceOf('SpiffyConnect\Client\OAuth2\AccessToken', $token);
        $this->assertEquals('foobar', $token->getAccessToken());
        $this->assertNull($token->getExpireTime());

        $client = $this->getTestClient('access_token_expires');
        $token  = $client->getAccessToken('foo', $code);
        $this->assertInstanceOf('SpiffyConnect\Client\OAuth2\AccessToken', $token);
        $this->assertEquals('foo', $token->getAccessToken());
        $this->assertEquals('bar', $token->getExpireTime());
    }

    /**
     * @cover SpiffyConnect\Client\OAuth2\Client::getRedirectResponse
     */
    public function testGetRedirectResponse()
    {
        $client   = $this->getTestClient('empty');
        $response = $client->getRedirectResponse('http://www.foo.bar');
        $this->assertInstanceOf('Zend\Stdlib\ResponseInterface', $response);
        $this->assertEquals(
            'Location: http://www.foo.bar/?client_id=id&redirect_uri=http%3A%2F%2Flocalhost%3A8080&scope=foobarrrrrr',
            $response->getHeaders()->get('Location')->toString()
        );

        $response = $client->getRedirectResponse('http://www.foo.bar', array('myparam' => 'iscool'));
        $this->assertInstanceOf('Zend\Stdlib\ResponseInterface', $response);
        $this->assertEquals(
            'Location: http://www.foo.bar/?myparam=iscool&client_id=id&redirect_uri=http%3A%2F%2Flocalhost%3A8080&scope=foobarrrrrr',
            $response->getHeaders()->get('Location')->toString()
        );
    }

    /**
     * @cover SpiffyConnect\Client\OAuth2\Client::getAccessToken
     */
    public function testGetAccessTokenWithMissingToken()
    {
        $code = new AuthorizationCode();
        $code->setCode('foobar');

        $this->setExpectedException('SpiffyConnect\Client\Exception\InvalidAccessTokenException');

        $client = $this->getTestClient('empty');
        $client->getAccessToken('foo', $code);
    }

    /**
     * @cover SpiffyConnect\Client\OAuth2\Client::getAccessToken
     */
    public function testGetAccessTokenInvalidResponse()
    {
        $code = new AuthorizationCode();
        $code->setCode('foobar');

        $this->setExpectedException('SpiffyConnect\Client\Exception\ResponseErrorException');

        $client = $this->getTestClient('404');
        $client->getAccessToken('foo', $code);
    }

    protected function getTestClient($mode)
    {
        $response = new Response();
        $client   = new Client(array(
            'client_id'     => 'id',
            'client_secret' => 'secret',
            'redirect_uri'  => 'http://localhost:8080',
            'scope'         => 'foobarrrrrr'
        ));
        $adapter = new TestAdapter();
        $client->getHttpClient()->setAdapter($adapter);

        switch ($mode) {
            case 'empty':
                $response->setStatusCode(200);
                break;

            case '404':
                $response->setStatusCode(404);
                break;

            case 'access_token':
                $response->setContent('{"access_token":"foobar"}');
                break;
            case 'access_token_expires':
                $response->setContent('{"access_token":"foo", "expires_in":"bar"}');
                break;
        }

        $adapter->setResponse($response);
        return $client;
    }
}