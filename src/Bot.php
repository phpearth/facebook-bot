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
 * @version 0.0.3
 */

namespace PHPWorldWide\FacebookBot;

use PHPWorldWide\FacebookBot\Connection\ConnectionManager;
use PHPWorldWide\FacebookBot\Connection\ConnectionParameters;

use PHPWorldWide\FacebookBot\Module\ModuleManager;

/**
 * The main class for the Facebook bot.
 */
class Bot
{
    /**
     * The connection manager.
     */
    private $connectionManager;

    /**
     * The module manager.
     */
    private $moduleManager;

    /**
     * Constructor.
     *
     * @param ConnectionParameters $connectionParameters
     */
    public function __construct(ConnectionParameters $connectionParameters)
    {
        $this->connectionManager = new ConnectionManager($connectionParameters);
        $this->moduleManager = new ModuleManager($this->connectionManager);
    }

    /**
     * Gets the module manager.
     */
    public function getModuleManager()
    {
        return $this->moduleManager;
    }

    /**
     * Gets the connection manager.
     */
    public function getConnectionManager()
    {
        return $this->connectionManager;
    }
}
