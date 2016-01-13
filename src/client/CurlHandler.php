<?php

namespace Mergado\ApiClient;

class Curlhandler {

	/** @cconst Number of retries before request fails **/
	const RETRY_COUNT = 2;

	/** @const Number of seconds to wait between request retries **/
	const RETRY_INTERVAL = 2;

	/** @const string User-Agent header value used in requests **/
	const USER_AGENT = 'MergadoApiClient';

	/** @var \MergadoApi\ILogger Requests logger **/
	private $logger;

	/**
	 * @var resource
	 *
	 * cURL handle - it is reused with each request in order to reuse HTTP
	 * connections (Keep-Alive).
	 */
	protected $curl;

	public function __construct(ILogger $logger = null) {

		$this->logger = $logger;
		$this->curl = curl_init();

	}

	/**
	 * Send general GET request and return JSON-decoded response data.
	 *
	 * @param string Target absolute URL
	 * @param array Additional HTTP headers
	 * @return object JSON-decoded response
	 */
	public function get($url, array $headers = array()) {

		$response = $this->request('GET', $url, $headers);
		return json_decode($response);

	}

	/**
	 * Send general PATCH request and return JSON-decoded response data.
	 * Request data are automatically JSON-encoded.
	 *
	 * @param string Target absolute URL
	 * @param mixed Request data
	 * @param array Additional HTTP headers
	 * @return object JSON-decoded response
	 */
	public function post($url, $data, array $headers = array()) {

		$headers['Content-Type'] = 'application/json';
		$json = json_encode($data);
		$response = $this->request('POST', $url, $headers, $json);

		return json_decode($response);

	}

	/**
	 * Send general PATCH request and return JSON-decoded response data.
	 * Request data are automatically JSON-encoded.
	 *
	 * @param string Target absolute URL
	 * @param mixed Request data
	 * @param array Additional HTTP headers
	 * @return object JSON-decoded response
	 */
	public function patch($url, $data, array $headers = array()) {

		$headers['Content-Type'] = 'application/json';
		$json = json_encode($data);
		$response = $this->request('PATCH', $url, $headers, $json);

		return json_decode($response);

	}

	/**
	 * Send general PATCH request and return JSON-decoded response data.
	 *
	 * @param string Target absolute URL
	 * @param array Additional HTTP headers
	 * @return object JSON-decoded response
	 */
	public function delete($url, array $headers = array()) {
		$response = $this->request('DELETE', $url, $headers);
		return json_decode($response);
	}

	/**
	 * Send request to remote URL and returns response content.
	 *
	 * @param string HTTP Method (GET, POST, PUT, etc.)
	 * @param string Remote URL
	 * @param array Additional HTTP headers
	 * @param string POST data included in request
	 * @return string Response content
	 * @throws HttpException If HTTP request fails (code >= 400)
	 */
	protected function request(
		string $method,
		string $url,
		array $headers = array(),
		$requestData = null
	) {

		foreach ($headers as $k => $v) {
			$headers[$k] = sprintf('%s: %s', $k, $v);
		}

		$options = array(

			CURLOPT_SSL_VERIFYPEER => (bool) $this->certificateFile,
			CURLOPT_SSL_VERIFYHOST => !$this->certificateFile ? 0 : 2,
			CURLOPT_CAINFO => realpath($this->certificateFile),

			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_URL => $url,
			CURLOPT_POSTFIELDS => $requestData,

			CURLOPT_USERAGENT => self::USER_AGENT,
			CURLOPT_HTTPHEADER => $headers,

			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_RETURNTRANSFER => true,

		);

		if ($this->logger) {
			$time = microtime(true);
		}

		for ($i = self::RETRY_COUNT; $i--;) {

			curl_setopt_array($this->curl, $options);

			$responseData = curl_exec($this->curl);
			$code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
			if ($responseData !== FALSE && $code < 400) {
				if ($this->logger) {
					$this->logger->logRequest($method, $url, json_decode($requestData), microtime(true) - $time, true, json_decode($responseData));
				}
				return $responseData;
			}

			// If NOT FOUND, then don't try again
			if ($code == 404) break;

			sleep($this->retryInterval);

		}

		if ($this->logger) {
			$this->logger->logRequest(
				$method,
				$url,
				json_decode($requestData),
				microtime(true) - $time
			);
		}

		throw new MergadoApiException($code, $responseData);

	}

}
