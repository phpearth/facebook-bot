<?php

/*
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPWorldWide\FacebookBot;

use PHPWorldWide\FacebookBot\Connection\Connection;

class Bot
{

    private $connection;

    /**
     * Constructor.
     *
     * @param Curl $curl
     *
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Runs the bot.
     *
     * @param Curl $curl
     */
    public function run()
    {
        while (true) {
            $this->connection->approveMember();
        }
    }
}
