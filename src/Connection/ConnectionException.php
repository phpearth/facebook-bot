<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.2
 * @version 0.0.2
 */

namespace PHPWorldWide\FacebookBot\Connection;

/**
 * Provides an exception to throw when a connection error occurs.
 */
class ConnectionException extends \Exception
{
	const ERR_NOTCONNECTED = 1;
	const ERR_OTHER = 2

	/**
	 * Creates a new instance.
	 *
	 * @param string $message A human-readable description for the exception.
	 * @param int $code An identifier used to quickly determine the reason for this exception (by
	 *                  a computer program). The value must be one of the constants 
	 *                  ERR_NOTCONNECTED or ERR_OTHER.
	 */
	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}
}