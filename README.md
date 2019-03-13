# Zoho Provider for OAuth 2.0 Client

This package provides Zoho OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).


## Requirements

The following versions of PHP are supported.

* PHP 7.0

## Usage

### Authorization Code Flow

```php
require_once 'vendor/autoload.php';

$provider = Postsmtp\OAuth2\Client\Provider\Zoho([
    'clientId'                => $client_id,    // The client ID assigned to you by the provider
    'clientSecret'            => $client_secret,   // The client password assigned to you by the provider
    'redirectUri'             => 'http://domain.com',
]);

// If we don't have an authorization code then get one
if (!isset($_GET['code'])) {

    $authorizationUrl = $provider->getAuthorizationUrl(
        [
            'scope' => 'ZohoMail.accounts.READ,ZohoMail.messages.READ',
            //Must use for refresh token
            'access_type' => 'offline'
        ]
    );

    // Get the state generated for you and store it to the session.
    $_SESSION['oauth2state'] = $provider->getState();

    // Redirect the user to the authorization URL.
    header('Location: ' . $authorizationUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {

    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }

    exit('Invalid state');

} else {

    try {

        // Try to get an access token using the authorization code grant.
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code']
        ]);

        // We have an access token, which we may use in authenticated
        // requests against the service provider's API.
        echo 'Access Token: ' . $accessToken->getToken() . "<br>";
        echo 'Refresh Token: ' . $accessToken->getRefreshToken() . "<br>";
        echo 'Expired in: ' . $accessToken->getExpires() . "<br>";
        echo 'Already expired? ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";
        echo 'More values: <pre>' . print_r( $accessToken->getValues(), true ) . "</pre><br>";

        // Using the access token, we may look up details about the
        // resource owner.
        $resourceOwner = $provider->getResourceOwner($accessToken);

        var_export($resourceOwner->toArray());

    } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
        exit($e->getMessage());

    }

}
```

### Refreshing a Token

For refresh token pass `access_type=offline`.


## Credits

- [Yehuda Hassine](https://postmansmtp.com)


## License

The MIT License (MIT). Please see [License File](https://github.com/yehudah/oauth2-zoho/blob/master/LICENSE) for more information.
