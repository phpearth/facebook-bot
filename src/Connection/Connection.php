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
 * @version 0.0.4
 */

namespace PHPWorldWide\FacebookBot\Connection;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 * Connection to the Facebook network. A connection is not thread-safe, so don't attempt to use 
 * them accross threads. Use the thread-manager to create connections in multi-threaded
 * applications.
 */
class Connection
{
    const REQ_SIMPLE = 1;
    const REQ_LITE = 2;
    const REQ_GRAPH = 3;

    const SESSION_CURL = 1;
    const SESSION_GRAPH = 2;

    /**
     * The connection parameters.
     */
    private $parameters;

    /**
     * The current connection state
     */
    private $state;

    /**
     * Constructor
     *
     * @param string $email Email to login to Facebook account
     * @param string $password Password to login to Facebook account
     * @param string $group_id The group to manage
     * @param boolean $debug Set debuging on or off
     *
     */
    public function __construct(ConnectionParameters $parameters)
    {
        $this->connectionParameters = $parameters;
        $this->state = new DisconnectedConnectionState();
    }

    /**
     * Sends a HTTP request and returns the resulting HTTP response. This operation requires the
     * state to be connected.
     *
     * @param string $type The type of request to perform. This should be one of REQ_SIMPLE, 
     *                     REQ_LITE or REQ_GRAPH.
     * @param string $path The path of the request.
     * @param string $method The method of the HTTP request.
     * @param array $data The request parameters to send.
     *
     * @return string The response text.
     * @throws ConnectionException when the request could not be performed.
     */
    public function request($type, $path, $method, $data = [])
    {
        $path = $this->buildPath($path);
        
        return $this->state->request($this, $type, $path, $method, $data);
    }

    /**
     * Connects to Facebook using the stored credentials. Once connected, requests can be sent.
     *
     * @param string $email The login email
     * @param string $password The login password
     */
    public function connect()
    {
        $this->state->connect($this, $this->parameters);
    }

    /**
     * Disconnects from Facebook. Once disconnected, requests cannot be send until the connection
     * has been restored.
     */
    public function disconnect()
    {
        $this->state->disconnect($this);
    }

    /**
     * Sets the state of the connection. Do *not* use this method unless you know what you're
     * doing. Read about the State design pattern for more information.
     *
     * @todo Make this private and allow friend-access by ConnectionState objects.
     */
    public function setState(ConnectionState $state)
    {
        $this->state = $state;
    }

    /**
     * Builds the final path by replacing any connection-related parameters.
     */
    private function buildPath($path) 
    {
        return str_replace('{group_id}', $this->parameters->getGroupId(), $path);
    }
}
