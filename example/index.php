<?php

use MergadoClient\ApiClient;
use MergadoClient\HttpClient;
use MergadoClient\OAuth2\MergadoProvider;

require_once '../vendor/autoload.php';

/**
 * Get these from your environment setup (.env, .neon files)
 * DON'T KEEP THEM IN CODE
 */
const MERGADO_OAUTH_URL = 'https://app.mergado.com/oauth2';
const MERGADO_API_URL = 'https://api.mergado.com';
const CLIENT_ID = 'client_id_for_mergado_platform';
const CLIENT_SECRET = 'client_secret_for_mergado_platform';
const REDIRECT_URI = 'https://example.com/path-to-your-app';

$provider = new MergadoProvider([
		'oAuthEndpoint' => MERGADO_OAUTH_URL,
		'clientId' => CLIENT_ID,
		'clientSecret' => CLIENT_SECRET,
		'redirectUri' => REDIRECT_URI,
	]);

if (!isset($_GET['code'])) {

	// Fetch the authorization URL from the provider; this returns the
	// urlAuthorize option and generates and applies any necessary parameters
	// (e.g. state).
	$authorizationUrl = $provider->getAuthorizationUrl([
		'entity_id' => 1,
	]);

	// Redirect the user to the authorization URL.
	HttpClient::redirect($authorizationUrl, 301);

}
else {

	try {

		// Try to get an access token using the authorization code grant.
		$accessToken = $provider->getAccessToken('authorization_code', [
			'code' => $_GET['code'],
		]);

		// We have an access token, which we may use in authenticated
		// requests against the service provider's API.
		echo 'Token: ' . $accessToken->getToken() . "<br>";
		echo 'Token expiration: ' . $accessToken->getExpires() . "<br>";
		echo 'Token is: ' . ($accessToken->hasExpired() ? 'expired' : 'not expired') . "<br>";


		// https://api.mergado.com/apps/
		// This doesn't require any scope
		$response = ApiClient::call($accessToken->getToken(), MERGADO_API_URL)
			->apps
			->get();

		echo '<br><br><br>';
		echo 'Apps in Mergado Store:<br>';
		foreach ($response->data as $app) {
			echo '- ' . $app->title . '<br>';
		}

		// https://api.mergado.com/me/
		// This requires user.read scope
		$user = ApiClient::call($accessToken->getToken(), MERGADO_API_URL)
			->me
			->get();

		echo '<br><br><br>';
		echo 'Currently logged in user name: ' . $user->name . '<br>';
		echo 'Currently logged in user e-mail: ' . $user->email . '<br>';

		// https://api.mergado.com/users/6/shops/
		// This requires user.shops.read scope

		$userShops = ApiClient::call($accessToken->getToken(), MERGADO_API_URL)
			->users(1)
			->shops()
			->limit(100)
			->offset(0)
			->get();

		echo '<br><br><br>';
		echo 'User\'s eshops:<br>';
		foreach ($userShops->data as $eshop) {
			echo '- ' . $eshop->name . '<br>';
		}

	} catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

		// Failed to get the access token or user details.
		exit($e->getMessage());

	}

}
