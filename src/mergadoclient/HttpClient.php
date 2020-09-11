<?php

namespace MergadoClient;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use MergadoClient\Exception\UnauthorizedException;
use MergadoClient\OAuth2\AccessToken;

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
    public function __construct($token)
    {
        $this->token = $token;
    }


    /**
     * @param $url
     * @param string $method
     * @param array $data
     * @return array
     *
     * can throw GuzzleHttp\Exception\ServerException for 500 level errors - extends from GuzzleHttp\Exception\BadResponseException.
     *
     * can throw GuzzleHttp\Exception\ClientException $e -> for 400 level errors
     * - Extends from GuzzleHttp\Exception\BadResponseException
     * and GuzzleHttp\Exception\BadResponseException extends from GuzzleHttp\Exception\RequestException.
     *
     * can throw GuzzleHttp\Exception\RequestException $e -> networkking errors - extends from GuzzleHttp\Exception\TransferException
     *
     * can throw GuzzleHttp\Exception\TooManyRedirectsException - extends GuzzleHttp\Exception\RequestException
     * can throw other Excetion $e -> other
     */
    public function request($url, $method = 'GET', $data = [])
    {

        $stack = HandlerStack::create();
        $client = new Client(['handler' => $stack]);

        $response = $client->request($method, $url, [
            'http_errors' => true,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => $data,
            'content-type' => 'application/json',
            "synchronous" => true,
        ]);

        $data = json_decode($response->getBody());
        return $data;

    }

    /**
     * Guzzle async request
     * @param $url
     * @param string $method
     * @param array $data
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function requestAsync($url, $method = 'GET', $data = [])
    {

        $stack = HandlerStack::create();
        $stack->push(ApiMiddleware::auth());
        $client = new Client(['handler' => $stack]);

        $promise = $client->requestAsync($method, $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => $data,
            'content-type' => 'application/json'
        ]);

        return $promise;

    }

    /**
     * Curl request - instead of Guzzle
     * @param $url
     * @param string $method
     * @param array $data
     * @return array|mixed
     * @throws UnauthorizedException
     * @throws \Exception
     */
    public function requestCurl($url, $method = 'GET', $data = [])
    {
        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token)
        );

        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $result = json_decode($result);

        $result = array_merge((array)$result, ["status_code" => $httpcode]);

        if ($httpcode == 401 || $httpcode == 403) {
            throw new UnauthorizedException("Unauthorized");
        } elseif ($httpcode > 403) {
            throw new \Exception("Status_code: " . $httpcode);
        }

        return $result;
    }


    /**
     * @param AccessToken $token
     * @return $this
     */
    public function setToken(AccessToken $token)
    {
        $this->token = $token;
        return $this;
    }

    public static function redirect($authorizationUrl, $code = 301) {
        header('Location:' . $authorizationUrl, true, $code);
        die;
    }

}