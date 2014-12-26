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

class Bot
{
    private $connection;
    private $memberRequestHandler;

    /**
     * Constructor.
     *
     * @param Connection $connection
     *
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
        $this->memberRequestHandler = new MemberRequestHandler();
    }

    /**
     * Runs the bot.
     */
    public function run()
    {
        while (true) {
            try 
            {
                $this->memberRequestHandler->run($this->connection);
            } 
            catch (ConnectionException $ex)
            {
                $this->connection->connect();
            }
        }
    }
}
