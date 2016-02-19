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
	public function __construct($token, $redirect_uri) {
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

		try {

			$response = $client->request($method ,$url, [
					'headers' => [
							'Authorization' =>  'Bearer '.$this->token
					],
					'json' => $data,
					'content-type' => 'application/json'
			]);
//			$response = $client->get('http://192.168.0.39/api/?access_token=wd');
//			var_dump($response->getStatusCode());

//			$data = json_decode($response->getBody());
			return $response;

		} catch(UnauthorizedException $e){

			//redirect to redirect_uri (your oauth endpoint)
//			$this->redirect($this->redirectUri);

		}

	}

	/**
	 * @param AccessToken $token
	 * @return $this
	 */
	public function setToken(AccessToken $token) {
		$this->token = $token;
		return $this;
	}

	/**
	 * @param $location
	 * @param $code
	 */
//	public static function redirect($location, $code = 301) {
//		header('Location: ' . $location, true, $code);
//		die();
//	}


}