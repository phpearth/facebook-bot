<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.4
 * @version 0.0.4
 */

namespace PHPWorldWide\FacebookBot\Connection\Request;

/**
 * Provides an interface for request adapters.
 */
interface Request
{
    /**
     * Executes the request and returns the result.
     */
    public function execute();
}