# OAuth v2 Client

The OAuth v2 clients follow the v2 specification and is consumed by OAuth2 providers.

## Quick Start

For a new client, just pass an array of configuration options to the constructor. You can modify these
options later by using the getOptions() accessor. For an exhaustive list of options, see the OAuth2
[ClientOptions](https://github.com/spiffyjr/spiffy-connect/tree/master/src/SpiffyConnect/Client/OAuth2/ClientOptions.php).

```php
<?php
use SpiffyConnect\Client\OAuth2\Client;

$client = new Client(array(
    'client_id'     => 'id',
    'client_secret' => 'secret',
    'redirect_uri'  => 'http://www.myhost.com/endpoint',
));
```
## Getting an authorization code

The general workflow for getting an authorization code involves redirecting the user for authentication
and then getting the authorization code from the query string redirect.

```php
<?php

if (isset($_GET['code'])) {
    $code = $client->getAuthorizationCode(); // simply pulls from the query string
} else {
    // get the redirect response to redirect the user
    $response = $client->getRedirectResponse('https://www.myprovider.com/oauth/auth');

    // use the client to redirect - this is optional, you can manage redirection yourself if you prefer
    $client->redirect($response);
}
```

## Getting an access token

After you have an authorization code you need to exchange it for an access token.

```php
<?php

$code = $client->getAuthorizationCode();
if ($code) {
    $token = $client->requestToken('https://www.myprovider.com/oauth/access_token', $code);
    $_SESSION['ACCESS_TOKEN'] = $token;
} else {
    // code isn't available for some reason!
}
```

## Making requests

Once you have your access token you are free to make requests to the provider.

```php
<?php

$token    = $_SESSION['ACCESS_TOKEN'];
$response = $client->request('https://www.myprovider.com/api/user');

echo $response->getBody();
```