<?php

namespace MergadoClient;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\HandlerStack;
use JsonSchema\Exception\ResourceNotFoundException;
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
    public function __construct($token)
    {
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
    public function request($url, $method = 'GET', $data = [])
    {

        $stack = HandlerStack::create();
        $stack->push(ApiMiddleware::auth());
        $client = new Client(['handler' => $stack]);

        $response = $client->request($method, $url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token
            ],
            'json' => $data,
            'content-type' => 'application/json',
            "synchronous" => true
        ]);

        $data = json_decode($response->getBody());
        return $data;

    }

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


}