<?php

namespace MergadoClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use League\OAuth2\Client\Token\AccessToken;
use MergadoClient\Exception\UnauthorizedException;

class HttpClient
{

	private $token;

	public function __construct(AccessToken $token = null) {
		$this->token = $token;
	}

	public function request($url, $method = 'GET', $data = []) {

		$stack = HandlerStack::create();
		$stack->push(ApiMiddleware::auth());
		$client = new Client(['handler' => $stack]);

		try {

			$response = $client->request($method ,$url, [
					'headers' => [
							'Auth' => $this->token->getToken()

					],
					'json' => $data,
					'content-type' => 'application/json'
			]);
//			$response = $client->get('http://192.168.0.39/api/?access_token=wd');
			var_dump($response->getStatusCode());

			$data = json_decode($response->getBody());
			return $data;

		} catch(UnauthorizedException $e){
			echo 'it is ON!';
			echo "\n";
			Auth::getAuth();
			echo 'auth? not realy, got 401';
			echo "\n";
		} catch(RequestException $e){
// 			To catch exactly error 400 use
			if ($e->getResponse()->getStatusCode() === '401') {
				echo "Got response 401";
				//authorize
//				Auth::getAuth();
			}
			echo $e->getResponse()->getStatusCode();
			echo "\n";

		} catch(\Exception $e){
			//log $e
			echo "\n";
			echo 'exceptional';
			echo "\n";echo "\n";echo "\n";
			var_dump($e);
		}

	}

	public function getJson(){

	}

	public function setToken(AccessToken $token) {
		$this->token = $token;
		return $this;
	}


}