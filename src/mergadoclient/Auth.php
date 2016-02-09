<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 12.1.16
 * Time: 9:57
 */

namespace MergadoClient;


use GuzzleHttp\Client;

class Auth {

	public function __construct($clientId, $clientSecret, $redirectUri) {

	}

	public static function getAuth(){
		$provider = new \League\OAuth2\Client\Provider\GenericProvider([
			'clientId'                => 'testclient',    // The client ID assigned to you by the provider
			'clientSecret'            => 'secret',   // The client password assigned to you by the provider
			'redirectUri'             => 'http://localhost/logbook/oauth2',
			'urlAuthorize'            => 'http://localhost/mergado/oauth2/authorize',
			'urlAccessToken'          => 'http://localhost/mergado/oauth2/access_token',
			'urlResourceOwnerDetails' => 'http://192.168.0.12/oauth2/lockdin/resource'
		]);

		echo 'shit';
		echo "\n";
// If we don't have an authorization code then get one
		if (!isset($_GET['code'])) {
			echo 'first';
			echo "\n";
			// Fetch the authorization URL from the provider; this returns the
			// urlAuthorize option and generates and applies any necessary parameters
			// (e.g. state).
			$authorizationUrl = $provider->getAuthorizationUrl();

			// Get the state generated for you and store it to the session.
			$_SESSION['oauth2state'] = $provider->getState();
//
//			var_dump($_SESSION);
//			var_dump($authorizationUrl);
			// Redirect the user to the authorization URL.


			$client = new Client();
			$response = $client->get($authorizationUrl);
//			var_dump($response);

			return $response;
//			return 'Location: ' . $authorizationUrl;
//			exit;

// Check given state against previously stored one to mitigate CSRF attack
		} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

			echo 'second';
			echo "\n";

			unset($_SESSION['oauth2state']);
			exit('Invalid state');

		} else {

			try {

				echo 'third';
				echo "\n";

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

				// Using the access token, we may look up details about the
				// resource owner.
				$resourceOwner = $provider->getResourceOwner($accessToken);

				var_export($resourceOwner->toArray());

				// The provider provides a way to get an authenticated API request for
				// the service, using the access token; it returns an object conforming
				// to Psr\Http\Message\RequestInterface.
				$request = $provider->getAuthenticatedRequest(
					'GET',
					'http://brentertainment.com/oauth2/lockdin/resource',
					$accessToken
				);

			} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

				// Failed to get the access token or user details.
				exit($e->getMessage());

			}

		}
	}
}