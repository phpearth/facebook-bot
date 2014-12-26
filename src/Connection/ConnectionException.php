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

class ConnectionException extends \Exception
{
	const ERR_NOTCONNECTED = 1;
	const ERR_OTHER = 2

	public function __construct($message, $code)
	{
		parent::__construct($message, $code);
	}
}