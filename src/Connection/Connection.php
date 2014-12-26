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

namespace PHPWorldWide\FacebookBot\Connection;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 * Connection to the Facebook network.
 */
class Connection implements ConnectionState
{
    /**
     * The login e-mail.
     */
    private $email;

    /**
     * The login password.
     */
    private $password;

    /**
     * The managed group id.
     */
    private $group_id;

    /**
     * Whether to enable debugging.
     */
    private $debug;

    /**
     * The current connection state
     */
    private $state;

    /**
     * A logger for debugging perposes
     */
    private $logger;

    /**
     * Constructor
     *
     * @param string $email Email to login to Facebook account
     * @param string $password Password to login to Facebook account
     * @param string $group_id The group to manage
     * @param boolean $debug Set debuging on or off
     *
     */
    public function __construct(string $email, string $password, string $group_id, boolean $debug = false)
    {
        $this->email = $email;
        $this->password = $password;
        $this->group_id = $group_id;
        $this->debug = $debug;

        $this->logger = new Logger('curl');
        $this->logger->pushHandler(new StreamHandler(__DIR__.'/../logs/curl.log', Logger::DEBUG));
    }

    /**
     * Sends a HTTP request and returns the resulting HTTP response. This operation requires the
     * state to be connected.
     *
     * @param string $url The url of the HTTP request.
     * @param string $method The method of the HTTP request.
     * @param array $data The request parameters to send.
     *
     * @return string The response text.
     * @throws ConnectionException when the request could not be performed.
     */
    public function request(string $url, string $method, array $data)
    {
        $this->state->request($this, $url, $method, $data);
    }

    /**
     * Connects to Facebook using the stored credentials. Once connected, requests can be sent.
     *
     * @param string $email The login email
     * @param string $password The login password
     */
    public function connect()
    {
        $this->state->connect($this, $email, $password);
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
     * Sets the state of the connection.
     */
    private function setState(ConnectionState $state)
    {
        $this->state = $state;
    }
}
