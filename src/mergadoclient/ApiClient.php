<?php

namespace MergadoClient;

/**
 * Class ApiClient
 * @package MergadoClient
 */
class ApiClient
{

    /**
     * @var UrlBuilder
     */
    private $urlBuilder;

    /**
     * @var HttpClient
     */
    private $http;

    /**
     * ApiClient constructor.
     * @param null $token
     * @param null $mode
     */
    public function __construct($token = null, $mode = null)
    {
        $this->urlBuilder = new UrlBuilder($mode);
        $this->http = new HttpClient($token);
    }

    /**
     * @param $token
     */
    public function setToken($token)
    {
        $this->http->setToken($token);
    }

    /**
     * @param $name
     * @return Api
     */
    public function __get($name)
    {
        $this->urlBuilder->appendFromProperty($name);
        return $this;
    }

    /**
     * @param $method
     * @param $args
     * @return Api
     */
    public function __call($method, $args)
    {
        $this->urlBuilder->appendFromMethod($method, $args);
        return $this;

    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {

    }

    /**
     * Get resource
     * @return string
     */
    public function get()
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->request($builtUrl, 'GET');
    }

    /**
     * @param array $data
     * @return array
     */
    public function post($data = [])
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->request($builtUrl, 'POST', $data);
    }

    /**
     * @param null $data
     * @return array
     */
    public function put($data = null)
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->request($builtUrl, 'PUT', $data);
    }

    /**
     * @param null $data
     * @return array
     */
    public function patch($data = null)
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->request($builtUrl, 'PATCH', $data);
    }

    /**
     * @return array
     */
    public function delete()
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->request($builtUrl, 'DELETE');
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function getAsync()
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestAsync($builtUrl, 'GET');
    }

    /**
     * @param array $data
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function postAsync($data = [])
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestAsync($builtUrl, 'POST', $data);
    }

    /**
     * @param null $data
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function putAsync($data = null)
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestAsync($builtUrl, 'PUT', $data);
    }

    /**
     * @param null $data
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function patchAsync($data = null)
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestAsync($builtUrl, 'PATCH', $data);
    }

    /**
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function deleteAsync()
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestAsync($builtUrl, 'DELETE');
    }

    /**
     * @return array|mixed
     * @throws Exception\UnauthorizedException
     * @throws \Exception
     */
    public function getCurl()
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestCurl($builtUrl, 'GET');
    }

    /**
     * @param array $data
     * @return array|mixed
     * @throws Exception\UnauthorizedException
     * @throws \Exception
     */
    public function postCurl($data = [])
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestCurl($builtUrl, 'POST', $data);
    }

    /**
     * @param null $data
     * @return array|mixed
     * @throws Exception\UnauthorizedException
     * @throws \Exception
     */
    public function putCurl($data = null)
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestCurl($builtUrl, 'PUT', $data);
    }

    /**
     * @param null $data
     * @return array|mixed
     * @throws Exception\UnauthorizedException
     * @throws \Exception
     */
    public function patchCurl($data = null)
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestCurl($builtUrl, 'PATCH', $data);
    }

    /**
     * @return array|mixed
     * @throws Exception\UnauthorizedException
     * @throws \Exception
     */
    public function deleteCurl()
    {
        $builtUrl = $this->urlBuilder->buildUrl();
        $this->urlBuilder->resetUrl();
        return $this->http->requestCurl($builtUrl, 'DELETE');
    }

    /**
     * @param $number
     * @return $this
     */
    public function limit($number)
    {
        $this->urlBuilder->addQueryParam('limit', $number);
        return $this;
    }

    /**
     * @param $number
     * @return $this
     */
    public function offset($number)
    {
        $this->urlBuilder->addQueryParam('offset', $number);
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function fields(array $fields)
    {
        $arr = $fields;
        $str = implode(",", $arr);
        $this->urlBuilder->addQueryParam('fields', $str);
        return $this;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function param($key, $value)
    {
        $this->urlBuilder->addQueryParam($key, $value);
        return $this;
    }

    /**
     * when using static call (eg. Api::call()->example->get();)
     * @return Api
     */
    public static function call()
    {
        return new ApiClient();
    }

}
