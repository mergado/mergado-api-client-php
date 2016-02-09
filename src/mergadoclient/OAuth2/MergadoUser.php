<?php

namespace MergadoClient\OAuth2\Provider;


use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class MergadoUser implements ResourceOwnerInterface
{

	/**
	 * Returns the identifier of the authorized resource owner.
	 *
	 * @return mixed
	 */
	public function getId() {
		// TODO: Implement getId() method.
	}

	/**
	 * Return all of the owner details available as an array.
	 *
	 * @return array
	 */
	public function toArray() {
		// TODO: Implement toArray() method.
	}
}