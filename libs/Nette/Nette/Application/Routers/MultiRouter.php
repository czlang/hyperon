<?php

/**
 * This file is part of the Nette Framework.
 *
 * Copyright (c) 2004, 2010 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license", and/or
 * GPL license. For more information please see http://nette.org
 * @package Nette\Application
 */



/**
 * The router broker.
 *
 * @author     David Grudl
 */
class NMultiRouter extends NArrayList implements IRouter
{
	/** @var array */
	private $cachedRoutes;

	/** @var string */
	private $module;



	public function __construct($module = NULL)
	{
		$this->module = $module ? $module . ':' : '';
	}



	/**
	 * Maps HTTP request to a NPresenterRequest object.
	 * @param  IHttpRequest
	 * @return NPresenterRequest|NULL
	 */
	public function match(IHttpRequest $httpRequest)
	{
		foreach ($this as $route) {
			$appRequest = $route->match($httpRequest);
			if ($appRequest !== NULL) {
				$appRequest->setPresenterName($this->module . $appRequest->getPresenterName());
				return $appRequest;
			}
		}
		return NULL;
	}



	/**
	 * Constructs absolute URL from NPresenterRequest object.
	 * @param  NPresenterRequest
	 * @param  NUri
	 * @return string|NULL
	 */
	public function constructUrl(NPresenterRequest $appRequest, NUri $refUri)
	{
		if ($this->cachedRoutes === NULL) {
			$routes = array();
			$routes['*'] = array();

			foreach ($this as $route) {
				$presenter = $route instanceof NRoute ? $route->getTargetPresenter() : NULL;

				if ($presenter === FALSE) continue;

				if (is_string($presenter)) {
					$presenter = strtolower($presenter);
					if (!isset($routes[$presenter])) {
						$routes[$presenter] = $routes['*'];
					}
					$routes[$presenter][] = $route;

				} else {
					foreach ($routes as $id => $foo) {
						$routes[$id][] = $route;
					}
				}
			}

			$this->cachedRoutes = $routes;
		}

		$presenter = strtolower($appRequest->getPresenterName());

		if ($this->module) {
			if (strncasecmp($presenter, $this->module, strlen($this->module)) === 0) {
				$appRequest = clone $appRequest;
				$appRequest->setPresenterName(substr($appRequest->getPresenterName(), strlen($this->module)));
			} else {
				return NULL;
			}
		}

		if (!isset($this->cachedRoutes[$presenter])) $presenter = '*';

		foreach ($this->cachedRoutes[$presenter] as $route) {
			$uri = $route->constructUrl($appRequest, $refUri);
			if ($uri !== NULL) {
				return $uri;
			}
		}

		return NULL;
	}



	/**
	 * Adds the router.
	 * @param  mixed
	 * @param  IRouter
	 * @return void
	 */
	public function offsetSet($index, $route)
	{
		if (!($route instanceof IRouter)) {
			throw new InvalidArgumentException("Argument must be IRouter descendant.");
		}
		parent::offsetSet($index, $route);
	}

}