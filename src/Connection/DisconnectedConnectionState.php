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
 * A disconnected state.
 */
class DisconnectedConnectionState extends ConnectionStateAbstract
{
	const LOGIN_URL = "https://login.facebook.com/login.php?login_attempt=1";

	public function request(string $url, string $method, array $data)
	{
        throw new ConnectionException("Unable to perform a request when disconnected.", ConnectionException::ERR_NOTCONNECTED);
	}

	public function connect(Connection $connection, string $email, string $password)
	{
		$data = [ 'email' => $email, 'pass' => $password ];

        $result = parent::doCurlRequest(LOGIN_URL, true, null, $data);
        preg_match('%Set-Cookie: ([^;]+);%', $result, $cookieData);
        $cookies = $cookieData[1];

        $result = parent::doCurlRequest(LOGIN_URL, true, $cookies, $data);
        preg_match_all('%Set-Cookie: ([^;]+);%', $result, $cookieData);
        $cookies = implode(';', $cookieData[1]);

        $connection->setState(new ConnectedConnectionState($cookies));
	}

	public function disconnect(Connection $connection)
	{
		// not connected: no action required.
	}
}