<?php

namespace MergadoClient;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use League\OAuth2\Client\Token\AccessToken;
use MergadoClient\Exception\UnauthorizedException;

/**
 * Class HttpClient
 * @package MergadoClient
 * manages http calls
 */
class HttpClient
{

	/**
	 * @var AccessToken|null
	 */
	private $token;

	/**
	 * HttpClient constructor.
	 * @param string AccessToken->getToken() | null $token
	 */
	public function __construct($token) {
		$this->token = $token;
	}


	/**
	 * @param $url
	 * @param string $method
	 * @param array $data
	 * @return array|mixed
	 *
	 * can throw UnauthorizedException $e -> 401 (catched by redirecting to oauth endpoint)
	 * can throw RequestException $e -> response with 4** or 5** othen than 401(Unauthorized Exception)
	 * can throw other Excetion $e -> other
	 */
	public function request($url, $method = 'GET', $data = []) {

		$stack = HandlerStack::create();
		$stack->push(ApiMiddleware::auth());
		$client = new Client(['handler' => $stack]);

		$response = $client->request($method, $url, [
				'headers' => [
						'Authorization' =>  'Bearer '.$this->token
				],
				'json' => $data,
				'content-type' => 'application/json',
				"synchronous" => true
		]);

		$data = json_decode($response->getBody());
		return $data;

	}

	public function requestAsync($url, $method = 'GET', $data = []) {

		$stack = HandlerStack::create();
		$stack->push(ApiMiddleware::auth());
		$client = new Client(['handler' => $stack]);

		$promise = $client->requestAsync($method, $url, [
				'headers' => [
						'Authorization' =>  'Bearer '.$this->token
				],
				'json' => $data,
				'content-type' => 'application/json'
		]);

		return $promise;

	}



	/**
	 * @param AccessToken $token
	 * @return $this
	 */
	public function setToken(AccessToken $token) {
		$this->token = $token;
		return $this;
	}


}