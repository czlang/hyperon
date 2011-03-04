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
 * Signal exception.
 *
 * @author     David Grudl
 */
class NBadSignalException extends NBadRequestException
{
	/** @var int */
	protected $defaultCode = 403;

}
