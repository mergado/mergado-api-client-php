<?php

namespace MergadoClient;

use MergadoClient\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;

class ApiMiddleware {

	/**
	 * Middleware that throws exceptions for 4xx or 5xx responses when the
	 * "http_error" request option is set to true.
	 *
	 * @return callable Returns a function that accepts the next handler.
	 */
	public static function auth()
	{
		return function (callable $handler) {
			return function ($request, array $options) use ($handler) {
				if (empty($options['http_errors'])) {
					return $handler($request, $options);
				}
				return $handler($request, $options)->then(
					function (ResponseInterface $response) use ($request, $handler) {
						$code = $response->getStatusCode();
						if ($code !== 401) {
							return $response;
						}
						throw UnauthorizedException::create($request, $response);
					}
				);
			};
		};
	}
}