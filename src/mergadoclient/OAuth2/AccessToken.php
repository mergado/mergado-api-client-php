<?php

namespace MergadoClient\OAuth2;

use InvalidArgumentException;
use RuntimeException;

/**
 * Represents an access token.
 *
 * @link http://tools.ietf.org/html/rfc6749#section-1.4 Access Token (RFC 6749, §1.4)
 */
class AccessToken extends \League\OAuth2\Client\Token\AccessToken implements \JsonSerializable {

	/**
	 * @var string
	 */
	protected $accessToken;

	/**
	 * @var int
	 */
	protected $expires;

	/**
	 * @var string
	 */
	protected $userId;

	/**
	 * @var
	 */
	protected $entityId;

	/**
	 * Constructs an access token.
	 *
	 * @param array $options An array of options returned by the service provider
	 *     in the access token request. The `access_token` option is required.
	 * @throws InvalidArgumentException if `access_token` is not provided in `$options`.
	 */
	public function __construct(array $options = []) {

		if (empty($options['access_token'])) {
			throw new InvalidArgumentException('Required option not passed: "access_token"');
		}

		$this->accessToken = $options['access_token'];

		if (!empty($options['user_id'])) {
			$this->userId = $options['user_id'];
		}

		if (!empty($options['entity_id'])) {
			$this->entityId = $options['entity_id'];
		}

		// We need to know when the token expires. Show preference to
		// 'expires_in' since it is defined in RFC6749 Section 5.1.
		// Defer to 'expires' if it is provided instead.
		if (!empty($options['expires_in'])) {
			$this->expires = time() + ((int) $options['expires_in']);
		} elseif (!empty($options['expires'])) {
			// Some providers supply the seconds until expiration rather than
			// the exact timestamp. Take a best guess at which we received.
			$expires = $options['expires'];

			if (!$this->isExpirationTimestamp($expires)) {
				$expires += time();
			}

			$this->expires = $expires;
		}
	}

	/**
	 * Check if a value is an expiration timestamp or second value.
	 *
	 * @param integer $value
	 * @return bool
	 */
	protected function isExpirationTimestamp($value) {

		// If the given value is larger than the original OAuth 2 draft date,
		// assume that it is meant to be a (possible expired) timestamp.
		$oauth2InceptionDate = 1349067600; // 2012-10-01
		return ($value > $oauth2InceptionDate);
	}

	/**
	 * Returns the access token string of this instance.
	 *
	 * @return string
	 */
	public function getToken() {
		return $this->accessToken;
	}

	/**
	 * Returns the expiration timestamp, if defined.
	 *
	 * @return integer|null
	 */
	public function getExpires() {
		return $this->expires;
	}

	/**
	 * Returns user_id
	 * @return string
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * Returns entity_id
	 * @return mixed
	 */
	public function getEntityId() {
		return $this->entityId;
	}

	/**
	 * Checks if this token has expired.
	 *
	 * @return boolean true if the token has expired, false otherwise.
	 * @throws RuntimeException if 'expires' is not set on the token.
	 */
	public function hasExpired() {

		$expires = $this->getExpires();

		if (empty($expires)) {
			throw new RuntimeException('"expires" is not set on the token');
		}

		return $expires < time();

	}

	/**
	 * Returns the token key.
	 *
	 * @return string
	 */
	public function __toString() {
		return (string) $this->getToken();
	}

	/**
	 * Returns an array of parameters to serialize when this is serialized with
	 * json_encode().
	 *
	 * @return array
	 */
	public function jsonSerialize() {

		$parameters = [];

		if ($this->accessToken) {
			$parameters['access_token'] = $this->accessToken;
		}

		if ($this->userId) {
			$parameters['user_id'] = $this->userId;
		}

		if ($this->entityId) {
			$parameters['entity_id'] = $this->entityId;
		}

		if ($this->expires) {
			$parameters['expires'] = $this->expires;
		}

		return $parameters;
	}

}
