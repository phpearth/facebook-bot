<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.3
 * @version 0.0.3
 */

namespace PHPWorldWide\FacebookBot\Module;

/**
 * Provides an exception to throw when a module error occurs.
 */
class ModuleException extends \Exception
{
	/**
	 * Creates a new instance.
	 *
	 * @param string $message A human-readable description for the exception.
	 */
	public function __construct($message)
	{
		parent::__construct($message);
	}
}