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
 * @version 0.0.2
 */

namespace PHPWorldwide\FacebookBot\Connection;

/**
 * A connected state. This state allows requests to be performed and sends a (list of) cookie(s) 
 * with them. These cookies were aquired during the connection.
 */
class ConnectedConnectionState extends ConnectionStateAbstract
{
    private $cookies;

    /**
     * Creates a new instance
     *
     * @param string $cookies The cookies to issue with requests.
     */
    public __construct(string $cookies)
    {
        $this->connection = $connection;
        $this->cookies = $cookies;
    }

    public function doRequest(Connection $connection, string $url, string $method, array $data = [])
    {
        try 
        {
            $result = $connection->doCurlRequest($url, $method, $data, true, $this->cookies);
        }
        catch (\Exception ex)
        {
            $connection->setState(new DisconnectedConnectionState());

            throw new ConnectionException(ex->getMessage(), ConnectionException::ERR_OTHER);
        }

        return $result;
    }

    public function connect(Connection $connection, string $username, string $password)
    {
        // Already connected: no action required.
    }

    public function disconnect(Connection $connection)
    {
        $connection->setState(new DisconnectedConnectionState());
    }
}