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

namespace PHPWorldWide\FacebookBot\Connection;

class ConnectionManager
{
    /**
     * The connection parameters.
     */
    public $connectionParameters;

    /**
     * Creates a new instance.
     *
     * @param ConnectionParameters $connectionParameters The connection parameters.
     */
    public function __construct(ConnectionParameters $connectionParameters)
    {
        $this->connectionParameters = $connectionParameters;
    }

    /**
     * Creates a new connection.
     *
     * @return Connection The new connection.
     */
    public function createConnection()
    {
        return new Connection($this->connectionParameters);
    }
}
