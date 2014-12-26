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

namespace PHPWorldWide\FacebookBot\Connection;

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
    public function __construct($cookies)
    {
        $this->cookies = $cookies;
    }

    public function request(Connection $connection, $url, $method, $data = [])
    {
        try 
        {
            $result = parent::doCurlRequest($url, $method, $data, null, $this->cookies);
        }
        catch (\Exception $ex)
        {
            $connection->setState(new DisconnectedConnectionState());

            throw new ConnectionException($ex->getMessage(), ConnectionException::ERR_OTHER);
        }

        return $result;
    }

    public function connect(Connection $connection, $username, $password)
    {
        // Already connected: no action required.
    }

    public function disconnect(Connection $connection)
    {
        $connection->setState(new DisconnectedConnectionState());
    }
}