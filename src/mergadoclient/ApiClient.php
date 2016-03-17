<?php

namespace MergadoClient;

use League\OAuth2\Client\Token\AccessToken;

class ApiClient
{

	private $urlBuilder;
	private $http;

	public function __construct($token = null, $mode = null) {

		$this->urlBuilder = new UrlBuilder($mode);
		$this->http = new HttpClient($token);
	}

	public function setToken($token) {
		$this->http->setToken($token);
	}

	/**
	 * @param $name
	 * @return Api
	 */
	public function __get($name) {
		$this->urlBuilder->appendFromProperty($name);
		return $this;
	}

	/**
	 * @param $method
	 * @param $args
	 * @return Api
	 */
	public function __call($method, $args) {
		$this->urlBuilder->appendFromMethod($method, $args);
		return $this;

	}

	public function setParams(array $params){

	}

	/**
	 * Get resource
	 * @return string
	 */
	public function get() {
		$builtUrl = $this->urlBuilder->buildUrl();
		$this->urlBuilder->resetUrl();
		return $this->http->request($builtUrl, 'GET');
	}

	public function post($data = []) {
		$builtUrl = $this->urlBuilder->buildUrl();
		$this->urlBuilder->resetUrl();
		return $this->http->request($builtUrl, 'POST', $data);
	}

	public function put($data = null) {
		$builtUrl = $this->urlBuilder->buildUrl();
		$this->urlBuilder->resetUrl();
		return $builtUrl;
	}

	public function delete() {
		$builtUrl = $this->urlBuilder->buildUrl();
		$this->urlBuilder->resetUrl();
		return $builtUrl;
	}

	/**
	 * when using static call (eg. Api::call()->example->get();)
	 * @return Api
	 */
	public static function call() {
		return new ApiClient();
	}

}
