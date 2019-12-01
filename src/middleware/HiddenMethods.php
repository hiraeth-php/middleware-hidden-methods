<?php

namespace Hiraeth\Middleware;

use Hiraeth;

use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * A middleware for normalizing the request method based on a hidden input
 */
class HiddenMethods implements Middleware
{
	/**
	 * A map of rquest methods to matching param values
	 *
	 * @var array
	 */
	protected $map = array();


	/**
	 * The parameter name to examine
	 *
	 * @var string|null
	 */
	protected $param = NULL;


	/**
	 * Create a new instacne of the middleware
	 */
	public function __construct(string $param, array $map)
	{
		$this->param = $param;
		$this->map   = $map;
	}


	/**
	 * {@inheritDoc}
	 */
	public function process(Request $request, Handler $handler): Response
	{
		if (isset($request->getParsedBody()[$this->param])) {
			$action = $request->getParsedBody()[$this->param];

		} elseif (isset($request->getQueryParams()[$this->param])) {
			$action = $request->getQueryParams()[$this->param];

		} else {
			$action = NULL;

		}

		if ($request->getMethod() == 'POST') {
			foreach ($this->map as $method => $actions) {
				if (in_array($action, $actions)) {
					$request = $request->withMethod($method);
					break;
				}
			}
		}

		return $handler->handle($request);
	}
}
