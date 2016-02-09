<?php

namespace Mergado\ApiClient;

class Request extends Object {

	/** @var CurlHandler CurlHandler object used for executing requests **/
	protected $curlHandler;

	/** @var string The URL this Request object is building **/
	protected $url;

	public function __construct(CurlHandler $curlHandler, string $baseUrl = null) {
		$this->curlHandler = $curlHandler;
		$this->url = $baseUrl;
	}

	public function __get(string $part) {
		$this->url .= self::leadingSlash($part);
		return $this;
	}

	public function __call(string $part, array $args) {

		if (count($args) > 1) {
			throw new MergadoApiClientException('Cannot have more than 1 parameter when calling an API method.');
		}

		$this->url .= self::leadingSlash($part) . (isset($args[0]) ? self::leadingSlash($args[0]) : null);
		return $this;

	}

	protected static function leadingSlash($string) {
		return '/' . $string;
	}

	public function get() {
		echo sprintf('%s: %s', 'GET', $this->url) . PHP_EOL;
		// return $this->curlHandler->get($this->url);
	}

	public function post(array $data = array()) {
		echo sprintf('%s: %s', 'POST', $this->url) . PHP_EOL;
		// return $this->curlHandler->post($this->url, $data);
	}

	public function patch(array $data = array()) {
		echo sprintf('%s: %s', 'PATCH', $this->url) . PHP_EOL;
		// return $this->curlHandler->patch($this->url, $data);
	}

	public function delete(array $data = array()) {
		echo sprintf('%s: %s', 'DELETE', $this->url) . PHP_EOL;
		// return $this->curlHandler->delete($this->url, $data);
	}

}
