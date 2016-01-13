<?php

namespace Mergado\ApiClient;

class Client extends Object {

	const API_BASE_URL = 'http://app.mergado.com/api';

	protected $curlHandler;

	public function __construct() {

		// Have a single CurlHandler for the MergadoApi client.
		$this->curlHandler = new CurlHandler;

	}

	/**
	 * Provide API for creating a new request.
	 * Any arguments used when invoking the Client as a function
	 * will be added to the returned request object.
	 *
	 * @param ... URL parts to add to the request object
	 * @return Request Request object to base any additional work on
	 */
	public function __invoke(...$args) {

		return $this->newRequest(implode('/', $args));

	}

	protected function newRequest(string $part = null) {

		// If an additional URL part is provided, add it to
		// the request URL right away.
		$additional = $part ? '/' . $part : null;

		return new Request(
			$this->curlHandler,
			self::API_BASE_URL . $additional
		);

	}

}
