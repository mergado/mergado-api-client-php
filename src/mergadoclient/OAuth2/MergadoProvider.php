<?php

namespace MergadoClient\OAuth2;

use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use MergadoClient\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;

class MergadoProvider extends AbstractProvider {

	/**
	 * Can be replaced with 'oAuthEndpoint' option
	 */
	const DEFAULT_BASE_URL = 'https://app.mergado.com/oauth2';

	public function __construct(array $options = [], array $collaborators = []) {

		$this->assertRequiredOptions($options);

		foreach ($options as $key => $value) {
			$this->$key = $value;
		}

		parent::__construct($options, $collaborators);

	}

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
		return $this->getBaseMergadoUrl() . '/token';
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
		return [];
	}

	/**
	 * Checks a provider response for errors.
	 *
	 * @param ResponseInterface $response
	 * @param array|string $data Parsed response data
	 * @return void
	 * @throws IdentityProviderException
	 */
	protected function checkResponse(ResponseInterface $response, $data) {

	}

	/**
	 * Get the base Facebook URL.
	 *
	 * @return string
	 */
	private function getBaseMergadoUrl() {

		$url = $this->oAuthEndpoint;
		return $url ?? self::DEFAULT_BASE_URL;

	}

	/**
	 * Verifies that all required options have been passed.
	 *
	 * @param array $options
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
			'clientId',
			'clientSecret',
			'redirectUri',
		];
	}

	/**
	 * Returns authorization parameters based on provided options.
	 *
	 * @param array $options
	 * @return array Authorization parameters
	 */
	protected function getAuthorizationParameters(array $options) {

		if (empty($options['entity_id'])) {
			$options['entity_id'] = '';
		}

		$options += [
			'response_type' => 'code',
			'approval_prompt' => 'auto',
		];

		return [
			'client_id' => $this->clientId,
			'redirect_uri' => $this->redirectUri,
			'entity_id' => $options['entity_id'],
			'response_type' => $options['response_type'],
			'approval_prompt' => $options['approval_prompt'],
		];

	}

	/**
	 * Requests an access token using a specified grant and option set.
	 *
	 * @param mixed $grant
	 * @param array $options
	 * @return \MergadoClient\OAuth2\AccessToken
	 */
	public function getAccessToken($grant, array $options = []) {

		if ($grant == 'offline_token') {
			$grant = 'refresh_token';
			if (isset($options["entity_id"])) {
				$options["refresh_token"] = base64_encode($options["entity_id"]);
				unset($options["entity_id"]);
			}
		}
		elseif ($grant == 'refresh_token') {
			if (isset($options["entity_id"])) {
				$options["refresh_token"] = base64_encode($options["entity_id"]);
				unset($options["entity_id"]);
			}
		}

		return parent::getAccessToken($grant, $options);
	}

	/**
	 * Creates an access token from a response.
	 *
	 * The grant that was used to fetch the response can be used to provide
	 * additional context.
	 *
	 * @param array $response
	 * @param AbstractGrant $grant
	 * @return \MergadoClient\OAuth2\AccessToken
	 */
	protected function createAccessToken(array $response, AbstractGrant $grant) {
		return new \MergadoClient\OAuth2\AccessToken($response);
	}

	/**
	 * Returns the URL for requesting the resource owner's details.
	 *
	 * @param AccessToken $token
	 * @return string
	 */
	public function getResourceOwnerDetailsUrl(AccessToken $token) {
		// TODO: Implement getResourceOwnerDetailsUrl() method.
	}

	/**
	 * Generates a resource owner object from a successful resource owner
	 * details request.
	 *
	 * @param array $response
	 * @param AccessToken $token
	 * @return ResourceOwnerInterface
	 */
	protected function createResourceOwner(array $response, AccessToken $token) {
		// TODO: Implement createResourceOwner() method.
	}

}
