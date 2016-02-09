<?php

namespace MergadoClient;

use GuzzleHttp\Client;
use League\OAuth2\Client\Token\AccessToken;
use MergadoClient\OAuth2\MergadoProvider;

class Api
{

	private $urlBuilder;
	private $http;
	private $handleAuth;

	public function __construct(AccessToken $token = null, $handleAuth = false) {

		$oauth = new MergadoProvider([
			'client_id' => 'logbook',
			'client_secret' => 'secret',
			'redirect_uri' => 'http://localhost/logbook/public/oauth2'
		]);
		
		$this->urlBuilder = new UrlBuilder();
		$this->http = new HttpClient($token);
		$this->handleAuth;
	}

	public function setToken(AccessToken $token) {
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
		echo $builtUrl;
		echo "\n";
		$client = new HttpClient();
		return $client->request($builtUrl, 'GET');
	}

	public function post($data = []) {
		$builtUrl = $this->urlBuilder->buildUrl();
		$this->urlBuilder->resetUrl();
		echo $builtUrl;
		echo "\n";
		$client = new HttpClient();
		return $client->request($builtUrl, 'POST', $data);
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
		return new Api();
	}

	/**
	 * @return \Psr\Http\Message\StreamInterface
	 */
	public function foo() {

		$client = new Client();

		$res = $client->request('GET', 'http://ipinfo.io');

		return $res->getBody();

	}

}
