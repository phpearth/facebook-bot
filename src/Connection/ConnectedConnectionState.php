<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Peter Kokot 
 * @author  Dennis Degryse
 * @since   0.0.2
 * @version 0.0.4
 */

namespace PHPWorldWide\FacebookBot\Connection;

use PHPWorldWide\FacebookBot\Connection\Request\CURLRequest;
use PHPWorldWide\FacebookBot\Connection\Request\FacebookGraphRequest;

/**
 * A connected state. This state allows requests to be performed and sends a (list of) cookie(s) 
 * with them. These cookies were aquired during the connection.
 */
class ConnectedConnectionState implements ConnectionState
{
    /**
     * The list of sessions.
     */
    private $sessions;

    /**
     * Creates a new instance
     *
     * @param array $sessions The list of sessions.
     */
    public function __construct($sessions)
    {
        $this->sessions = $sessions;
    }

    public function request(Connection $connection, $type, $path, $method, $data = [])
    {
        try {
            $request = $this->createRequest($type, $path, $method, $data);
            $result = $request->execute();
        } catch (\Exception $ex) {
            $connection->setState(new DisconnectedConnectionState());

            throw new ConnectionException($ex->getMessage(), ConnectionException::ERR_OTHER);
        }

        return $result;
    }

    public function connect(Connection $connection, ConnectionParameters $connectionParameters)
    {
        // Already connected: no action required.
    }

    public function disconnect(Connection $connection)
    {
        $connection->setState(new DisconnectedConnectionState());
    }

    /**
     * Factory method that creates the correct request corresponding to the given type.
     *
     * @param string $type The type of request to perform. This should be one of REQ_SIMPLE, 
     *                     REQ_LITE or REQ_GRAPH.
     * @param string $url The url of the HTTP request.
     * @param string $method The method of the HTTP request.
     * @param array $data The request parameters to send.
     *
     * @return Request The request.
     */
     */
    private function createRequest($type, $path, $method, $data = [])
    {
        $request = null;

        switch ($type) {
            case Connection::REQ_SIMPLE:
                $session = $this->sessions[Connection::SESSION_GRAPH];
                $request = new FacebookGraphRequest($path, $method, $session, $data);
                break;

            case Connection::REQ_LITE:
                $baseUrl = 'https://m.facebook.com';
                $session = $this->sessions[Connection::SESSION_CURL];
                $request = new CURLRequest($baseUrl, $path, $method, $session, $data);
                break;

            case Connection::REQ_GRAPH:
                $baseUrl = 'https://www.facebook.com';
                $session = $this->sessions[Connection::SESSION_CURL];
                $request = new CURLRequest($baseUrl, $path, $method, $session, $data);
                break;
        }

        return $request;
    }
}