<?php

namespace MergadoClient;

/**
 * Class UrlBuilder
 * @package MergadoClient
 */
class UrlBuilder {

	/**
	 * @var string
	 */
	const DEFAULT_BASE_URL = 'https://api.mergado.com';

	/**
	 * @var
	 */
	protected $url;

	/**
	 * @var null
	 */
	protected $baseUrl = self::DEFAULT_BASE_URL;

	/**
	 * @var array
	 */
	protected $queryParams = [];

	/**
	 * UrlBuilder constructor.
	 * @param ?string $baseUrl
	 */
	public function __construct($baseUrl = null) {

		if ($baseUrl && strpos($baseUrl, 'http') === 0) {
			$this->baseUrl = $baseUrl;
		}
		$this->resetUrl();

	}

	/**
	 * Sets $this->url to base
	 */
	public function resetUrl() {
		$this->url = $this->baseUrl;
	}

	/**
	 * @param $method
	 * @param array $args
	 * @return $this
	 */
	public function appendFromMethod($method, array $args) {

		$this->url .= '/' . strtolower(urlencode($method));

		if ($args) {
			$this->url .= '/' . urlencode($args[0]);
		}
		return $this;

	}

	/**
	 * @param $name
	 * @return $this
	 */
	public function appendFromProperty($name) {
		$this->url .= '/' . strtolower(urlencode($name));
		return $this;
	}

	/**
	 * @return string
	 */
	public function buildUrl() {

		$builtUrl = $this->url;
		$builtUrl .= "/";
		$this->resetUrl();

		foreach ($this->queryParams as $key => $value) {
			$parsedUrl = parse_url($builtUrl);
			$separator = (!isset($parsedUrl['query'])) ? '?' : '&';
			$builtUrl .= $separator . $key . "=" . $value;
		}

		$this->queryParams = [];

		return $builtUrl;

	}

	/**
	 * @param $key
	 * @param $value
	 * @return $this
	 */
	public function addQueryParam($key, $value) {
		$this->queryParams[$key] = $value;
		return $this;
	}

}
