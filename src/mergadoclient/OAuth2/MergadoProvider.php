<?php

namespace MergadoClient\OAuth2;


use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

class MergadoProvider extends AbstractProvider {

	const BASEURL = 'http://lab.mergado.com/oauth2';
	const BASEURL_DEV = 'http://lab.mergado.com/oauth2';


	public function __construct(array $options = [], array $collaborators = []) {
		$this->assertRequiredOptions($options);

		foreach ($options as $key => $value) {
			$this->$key = $value;
		}

		parent::__construct($options, $collaborators);
	}

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
			'clientId',
			'clientSecret',
			'redirectUri',
		];
	}

	/**
	 * Returns authorization parameters based on provided options.
	 *
	 * @param  array $options
	 * @return array Authorization parameters
	 */
	protected function getAuthorizationParameters(array $options)
	{
		if (empty($options['state'])) {
			$options['state'] = $this->getRandomState();
		}

		if (empty($options['scope'])) {
			$options['scope'] = $this->getDefaultScopes();
		}

		if (empty($options['entity_id'])) {
			$options['entity_id'] = '';
		}

		$options += [
				'response_type'   => 'code',
				'approval_prompt' => 'auto'
		];

		if (is_array($options['scope'])) {
			$separator = $this->getScopeSeparator();
			$options['scope'] = implode($separator, $options['scope']);
		}

		// Store the state as it may need to be accessed later on.
		$this->state = $options['state'];

		return [
				'client_id'       => $this->clientId,
				'redirect_uri'    => $this->redirectUri,
				'entity_id'       => $options['entity_id'],
				'state'           => $this->state,
				'scope'           => $options['scope'],
				'response_type'   => $options['response_type'],
				'approval_prompt' => $options['approval_prompt'],
		];
	}


}