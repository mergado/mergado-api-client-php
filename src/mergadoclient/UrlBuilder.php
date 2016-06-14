<?php

namespace MergadoClient;


class UrlBuilder
{
    protected $url;

    protected $mode;

    protected $queryParams = [];

    const BASEURL = 'https://app.mergado.com/api';
    const BASEURL_DEV = 'https://app.mergado.com/api';
    const BASEURL_LOCAL = 'http://dev.mergado.com/api';

    public function __construct($mode = null)
    {

        $this->mode = $mode;

        $this->resetUrl();
    }

    public function resetUrl()
    {

        if ($this->mode == 'dev') {
            $this->url = static::BASEURL_DEV;
        } else if ($this->mode == 'local') {
            $this->url = static::BASEURL_LOCAL;
        } else {
            $this->url = static::BASEURL;
        }

    }

    /**
     * @param $method
     * @param array $args
     * @return $this
     */
    public function appendFromMethod($method, array $args)
    {
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
    public function appendFromProperty($name)
    {
        $this->url .= '/' . strtolower(urlencode($name));
        return $this;
    }


    /**
     * @return string
     */
    public function buildUrl()
    {
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

    public function addQueryParam($key, $value)
    {
        $this->queryParams[$key] = $value;
        return $this;
    }


}