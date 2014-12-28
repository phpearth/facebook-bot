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

use PHPWorldWide\FacebookBot\Connection\SessionBuider\FacebookSessionBuilder;
use PHPWorldWide\FacebookBot\Connection\SessionBuider\FacebookGraphSessionBuilder;

/**
 * A disconnected state.
 */
class DisconnectedConnectionState implements ConnectionState
{
	public function request(Connection $connection, $type, $url, $method, $data = [])
	{
        throw new ConnectionException("Unable to perform a request when disconnected.", ConnectionException::ERR_NOTCONNECTED);
	}

	public function connect(Connection $connection, ConnectionParameters $connectionParameters)
	{
		$sessionBuilders = [
            Connection::SESSION_CURL => new FacebookSessionBuilder($connectionParameters->getEmail(), $connectionParameters->getPassword),
            Connection::SESSION_GRAPH => new FacebookGraphSessionBuilder($connectionParameters->getAccessToken())
        ];

        $sessions = array_walk(function(&$item) { $item = $item->build() }, $sessionBuilders);

        $connection->setState(new ConnectedConnectionState($sessions));
	}

	public function disconnect(Connection $connection)
	{
		// not connected: no action required.
	}
}