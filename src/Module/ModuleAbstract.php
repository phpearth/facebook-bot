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

use PHPWorldWide\FacebookBot\Connection\Connection;
use PHPWorldWide\FacebookBot\Connection\ConnectionManager;

/**
 * Provides a threaded module base.
 */
abstract class ModuleAbstract extends \Thread implements Module
{
    private $connectionManager;
    private $stopRequested;

    /**
     * Creates a new instance.
     *
     * @param ConnectionManager $connectionManager The connection manager that can be used for
     *                                             creating new connections.
     */
    public function __construct(ConnectionManager $connectionManager) 
    {
        $this->connectionManager = $connectionManager;
    }

    /**
     * Spawns the module's logic in a new worker thread.
     */
    public function start()
    {
        $stopRequested = false;

        parent::start(PTHREADS_INHERIT_NONE);
    }

    /**
     * Requests a stop in a synchronized manner and joins the worker thread.
     */
    public function stop() 
    {
        $this->lock();
        $this->stopRequested = true;
        $this->unlock();

        $this->join();
    }

    /**
     * Runs the module in the new thread's context. This first loads the autoloader, which has been
     * discarded due to the PTHREADS_INHERIT_NONE flag. Then a new connection is created, which can
     * safely be used in this context for polling data and afterwards handling the polled entities.
     *
     * The thread runs until a stop has been requested. The connection is closed before joining.
     */
    public function run()
    {
        // The autoloader should be reloaded in the new thread.
        require_once __DIR__ . '/../../vendor/autoload.php';

        $connection = $this->connectionManager->createConnection();
        $connection->connect();

        while (!$this->isStopRequested())
        {
            $entities = $this->pollData($connection);

            foreach ($entities as $entity) {
                $this->handleEntity($connection, $entity);
            }
        }

        $connection->disconnect();
    }

    /**
     * Polls entities that are relevant to the module.
     *
     * @param Connection $connection The connection to use for retrieving entities.
     *
     * @return array A list of entities.
     */
    protected abstract function pollData(Connection $connection);

    /**
     * Handles a single entity.
     *
     * @param Connection $connection The connection to use for handling the entity.
     * @param object $entity The entity to handle.
     */
    protected abstract function handleEntity(Connection $connection, $entity);

    /**
     * Gets whether a stop has been requested in a synchronized manner.
     */
    private function isStopRequested()
    {
        $this->lock();
        $stopRequested = $this->stopRequested;
        $this->unlock();

        return $stopRequested;
    }
}