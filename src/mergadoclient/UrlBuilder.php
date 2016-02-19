<?php

namespace MergadoClient;


class UrlBuilder
{
	private $url;
//	const BASEURL = 'http://api.mergado.com';
//	const BASEURL = 'http://localhost/mergado/';
	const BASEURL_DEV = 'http://lab.mergado.com/api/';
	const BASEURL = 'http://lab.mergado.com/api/';

	public function __construct() {

		$this->resetUrl();
	}

	public function resetUrl() {

		$this->url = self::BASEURL;
	}

	/**
	 * @param $method
	 * @param array $args
	 * @return $this
	 */
	public function appendFromMethod($method, array $args) {
		$this->url .= '/'.strtolower(urlencode($method));
		if($args){
			if(gettype($args[0]) === "integer" ){
				$this->url .= '/'.urlencode($args[0]);
			}
		}

		return $this;
	}

	/**
	 * @param $name
	 * @return $this
	 */
	public function appendFromProperty($name) {
		$this->url .= '/'.strtolower(urlencode($name));
		return $this;
	}


	/**
	 * @return string
	 */
	public function buildUrl() {
		$builtUrl = $this->url.'/';
		$this->url = self::BASEURL;
		return $builtUrl;
	}

}