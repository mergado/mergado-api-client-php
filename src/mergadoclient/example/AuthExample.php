<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 12.1.16
 * Time: 9:57
 */

namespace MergadoClient;


use MergadoClient\OAuth2\MergadoProvider;

class Auth
{

    public function __construct($clientId, $clientSecret, $redirectUri)
    {

    }

    public static function getAccess()
    {
        $provider = new MergadoProvider([
            'clientId' => 'testclient',    // The client ID assigned to you by the provider
            'clientSecret' => 'secret',   // The client password assigned to you by the provider
            'redirectUri' => 'http://localhost/logbook/oauth2'
        ]);

//		If we don't have an authorization code then get one
        if (!isset($_GET['code'])) {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl();

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.
            HttpClient::redirect($authorizationUrl, 301);

//			Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

            unset($_SESSION['oauth2state']);
            exit('Invalid state');

        } else {

            try {

                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                // We have an access token, which we may use in authenticated
                // requests against the service provider's API.
                echo $accessToken->getToken() . "\n";
                echo $accessToken->getRefreshToken() . "\n";
                echo $accessToken->getExpires() . "\n";
                echo ($accessToken->hasExpired() ? 'expired' : 'not expired') . "\n";

            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

                // Failed to get the access token or user details.
                exit($e->getMessage());

            }

        }
    }
}