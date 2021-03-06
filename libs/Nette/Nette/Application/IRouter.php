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
 * The bi-directional router.
 *
 * @author     David Grudl
 */
interface IRouter
{
	/**#@+ flag */
	const ONE_WAY = 1;
	const SECURED = 2;
	/**#@-*/

	/**
	 * Maps HTTP request to a NPresenterRequest object.
	 * @param  IHttpRequest
	 * @return NPresenterRequest|NULL
	 */
	function match(IHttpRequest $httpRequest);

	/**
	 * Constructs absolute URL from NPresenterRequest object.
	 * @param  NPresenterRequest
	 * @param  NUri referential URI
	 * @return string|NULL
	 */
	function constructUrl(NPresenterRequest $appRequest, NUri $refUri);

}
