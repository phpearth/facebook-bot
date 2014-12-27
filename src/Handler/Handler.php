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

namespace PHPWorldWide\FacebookBot\Handler;

use PHPWorldWide\FacebookBot\Connection\Connection;

interface Handler
{
	public function run(Connection $connection);
}