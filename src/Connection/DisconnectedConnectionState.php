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
 * @version 0.0.3
 */

namespace PHPWorldWide\FacebookBot\Connection;

/**
 * A disconnected state.
 */
class DisconnectedConnectionState extends ConnectionStateAbstract
{
	const LOGIN_URL = "https://login.facebook.com/login.php?login_attempt=1";

	public function request(Connection $connection, $url, $method, $data = [])
	{
        throw new ConnectionException("Unable to perform a request when disconnected.", ConnectionException::ERR_NOTCONNECTED);
	}

	public function connect(Connection $connection, ConnectionParameters $connectionParameters)
	{
		$data = [ 
            'email' => $connectionParameters->getEmail(), 
            'pass' => $connectionParameters->getPassword() ];

        $result = parent::doCurlRequest(self::LOGIN_URL, "POST", $data, true);
        preg_match('%Set-Cookie: ([^;]+);%', $result, $cookieData);
        $cookies = $cookieData[1];

        $result = parent::doCurlRequest(self::LOGIN_URL, "POST", $data, true, $cookies);
        preg_match_all('%Set-Cookie: ([^;]+);%', $result, $cookieData);
        $cookies = implode(';', $cookieData[1]);

        $connection->setState(new ConnectedConnectionState($cookies));
	}

	public function disconnect(Connection $connection)
	{
		// not connected: no action required.
	}
}