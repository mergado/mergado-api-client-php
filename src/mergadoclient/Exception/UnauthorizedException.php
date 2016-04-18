<?php

namespace MergadoClient\Exception;

use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class UnauthorizedException extends \Exception
{
	/** @var RequestInterface */
	private $request;

	/** @var ResponseInterface */
	private $response;

	/** @var array */
	private $handlerContext;

	public function __construct(
		$message,
		RequestInterface $request = null,
		ResponseInterface $response = null,
		\Exception $previous = null
	) {
		// Set the code of the exception if the response is set and not future.
		$code = $response && !($response instanceof PromiseInterface)
			? $response->getStatusCode()
			: 0;
		parent::__construct($message, $code, $previous);
		$this->request = $request;
		$this->response = $response;
	}

	public static function create(
		RequestInterface $request,
		ResponseInterface $response = null,
		\Exception $previous = null
	) {
		if (!$response) {
			return new self(
				'Error completing request',
				$request,
				null,
				$previous
			);
		}

		$label = 'Unsuccessful request';
		$className = __CLASS__;
		// Server Error: `GET /` resulted in a `404 Not Found` response:
		// <html> ... (truncated)
		$message = sprintf(
			'%s: `%s` resulted in a `%s` response',
			$label,
			$request->getMethod() . ' ' . $request->getUri(),
			$response->getStatusCode() . ' ' . $response->getReasonPhrase()
		);


		return new $className($message, $request, $response, $previous);
	}


	/**
	 * Get the request that caused the exception
	 *
	 * @return RequestInterface
	 */
	public function getRequest()
	{
		return $this->request;
	}

	/**
	 * Get the associated response
	 *
	 * @return ResponseInterface|null
	 */
	public function getResponse()
	{
		return $this->response;
	}

	/**
	 * Check if a response was received
	 *
	 * @return bool
	 */
	public function hasResponse()
	{
		return $this->response !== null;
	}

}