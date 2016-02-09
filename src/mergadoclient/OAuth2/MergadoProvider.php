<?php

namespace MergadoClient\OAuth2;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class MergadoProvider extends AbstractProvider {

	const BASEURL = 'http://localhost/mergado/oauth2';
	const BASEURL_DEV = 'http://localhost/mergado/oauth2';


	public function __construct(array $options = [], array $collaborators = []) {
		$this->assertRequiredOptions($options);

		foreach ($options as $key => $value) {
			$this->$key = $value;
		}

		parent::__construct($options, $collaborators);
	}

	/**
	 * The Graph API version to use for requests.
	 *
	 * @var string
	 */
	protected $apiVersion;

	/**
	 * A toggle to enable the dev tier URL's.
	 *
	 * @var boolean
	 */
	private $devMode = false;


	/**
	 * Returns the base URL for authorizing a client.
	 *
	 * Eg. https://oauth.service.com/authorize
	 *
	 * @return string
	 */
	public function getBaseAuthorizationUrl() {
		return $this->getBaseMergadoUrl() . '/authorize';
	}

	/**
	 * Returns the base URL for requesting an access token.
	 *
	 * Eg. https://oauth.service.com/token
	 *
	 * @param array $params
	 * @return string
	 */
	public function getBaseAccessTokenUrl(array $params) {
		return $this->getBaseMergadoUrl() . '/access-token';
	}

	/**
	 * Returns the URL for requesting the resource owner's details.
	 *
	 * @param AccessToken $token
	 * @return string
	 */
	public function getResourceOwnerDetailsUrl(AccessToken $token) {

	}

	/**
	 * Returns the default scopes used by this provider.
	 *
	 * This should only be the scopes that are required to request the details
	 * of the resource owner, rather than all the available scopes.
	 *
	 * @return array
	 */
	protected function getDefaultScopes() {

	}

	/**
	 * Checks a provider response for errors.
	 *
	 * @throws IdentityProviderException
	 * @param  ResponseInterface $response
	 * @param  array|string $data Parsed response data
	 * @return void
	 */
	protected function checkResponse(ResponseInterface $response, $data) {

	}

	/**
	 * Generates a resource owner object from a successful resource owner
	 * details request.
	 *
	 * @param  array $response
	 * @param  AccessToken $token
	 * @return ResourceOwnerInterface
	 */
	protected function createResourceOwner(array $response, AccessToken $token) {

	}

	/**
	 * Get the base Facebook URL.
	 *
	 * @return string
	 */
	private function getBaseMergadoUrl() {
		return $this->devMode ? static::BASEURL_DEV : static::BASEURL;
	}

	/**
	 * Verifies that all required options have been passed.
	 *
	 * @param  array $options
	 * @return void
	 * @throws InvalidArgumentException
	 */
	private function assertRequiredOptions(array $options) {
		$missing = array_diff_key(array_flip($this->getRequiredOptions()), $options);

		if (!empty($missing)) {
			throw new \InvalidArgumentException(
				'Required options not defined: ' . implode(', ', array_keys($missing))
			);
		}
	}

	/**
	 * Returns all options that are required.
	 *
	 * @return array
	 */
	protected function getRequiredOptions() {
		return [
			'client_id',
			'client_secret',
			'redirect_uri',
		];
	}


}