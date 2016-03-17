<?php

namespace MergadoClient;


class UrlBuilder
{
	protected $url;

	protected $mode;

	const BASEURL = 'http://app.mergado.com/api';
	const BASEURL_DEV = 'http://dev.mergado.com/api';
	const BASEURL_LAB = 'http://lab.mergado.com/api';

	public function __construct($mode = null) {

		$this->resetUrl();
	}

	public function resetUrl() {

		if ($this->mode == 'dev') {
			return static::BASEURL_DEV;
		} else if ($this->mode == 'local') {
			return static::BASEURL_LAB;
		} else {
			return static::BASEURL;
		}

	}

	/**
	 * @param $method
	 * @param array $args
	 * @return $this
	 */
	public function appendFromMethod($method, array $args) {
		$this->url .= '/'.strtolower(urlencode($method));
		if($args){
			$this->url .= '/'.urlencode($args[0]);
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
		$builtUrl = $this->url;
		$this->resetUrl();
		return $builtUrl;
	}

}