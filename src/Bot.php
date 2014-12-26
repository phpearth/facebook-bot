<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Peter Kokot 
 * @author  Dennis Degryse
 * @since   0.0.1
 * @version 0.0.2
 */

namespace PHPWorldWide\FacebookBot;

use PHPWorldWide\FacebookBot\Connection\Connection;
use PHPWorldWide\FacebookBot\Connection\ConnectionException;

use PHPWorldWide\FacebookBot\Handler\MemberRequestHandler;

class Bot
{
    /**
     * The connection to (re)use.
     */
    private $connection;

    /**
     * The handlers to run.
     */
    private $handlers;

    /**
     * Constructor.
     *
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->handlers = [ new MemberRequestHandler() ];
    }

    /**
     * Runs all handlers in the bot.
     */
    public function run()
    {
        while (true) {
            try {
                foreach ($this->handlers as $handler) {
                    $handler->run($this->connection);
                }
            } catch (ConnectionException $ex) {
                $this->connection->connect();
            }
        }
    }
}
