# OAuth v1 Client

The OAuth v1 clients follow the 1.0a specification and is consumed by OAuth1 providers.

## Quick Start

For a new client, just pass an array of configuration options to the constructor. You can modify these
options later by using the getOptions() accessor. For an exhaustive list of options, see the OAuth1
[ClientOptions](https://github.com/spiffyjr/spiffy-connect/tree/master/src/SpiffyConnect/Client/Oauth1/ClientOptions.php).

```php
<?php
use SpiffyConnect\Client\Oauth1\Client;

$client = new Client(array(
    'client_id'     => 'id',
    'client_secret' => 'secret',
    'redirect_uri'  => 'http://www.myhost.com/endpoint',
));
```

Note: in order to provide a more consistent API the constructor takes `client_id`, `client_secret`, and
`redirect_uri` similar to the OAuth2 client.

## Getting a request token

The general workflow for getting a request token involves hitting the service provider, storing the
token, and then redirecting the user to the service provider for authentication.

```php
<?php

// returns an instance of RequestToken
$token = $client->getRequestToken('https://www.myprovider.com/oauth/request_token', 'POST');

$_SESSION['REQUEST_TOKEN'] = $token;

// get the redirect response to redirect the user
$response = $client->getRedirectResponse('https://www.myprovider.com/oauth/authenticate', $token);

// use the client to redirect - this is optional, you can manage redirection yourself if you prefer
$client->redirect($response);
```

## Getting an access token

After you have a request token you need to exchange it for an access token.

```php
<?php

$requestToken = $_SESSION['REQUEST_TOKEN'];
$accessToken  = $client->getAccessToken('https://www.myprovider.com/oauth/access_token', $requestToken);

$_SESSION['ACCESS_TOKEN'] = $accessToken;
unset($_SESSION['REQUEST_TOKEN']);
```

## Making requests

Once you have your access token you are free to make requests to the provider.

```php
<?php

$token    = $_SESSION['ACCESS_TOKEN'];
$response = $client->request('https://www.myprovider.com/api/user');

echo $response->getBody();
```