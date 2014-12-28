<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.2
 * @version 0.0.2
 */

namespace PHPWorldWide\FacebookBot\Connection;

/**
 * Provides an interface for connection states.
 */
interface ConnectionState 
{
	/**
	 * Sends a HTTP request and returns the resulting HTTP response. This operation requires the
	 * state to be connected.
	 *
	 * @param Connection $connection The connection on which to perform the operation.
	 * @param string $url The url of the HTTP request.
	 * @param string $method The method of the HTTP request.
	 * @param array $data The request parameters to send.
	 *
	 * @return string|boolean The response text or false in case something went wrong.
	 */
	public function request(Connection $connection, $url, $method, $data);

	/**
	 * Connects to Facebook using the stored credentials. Once connected, requests can be sent.
	 *
	 * @param Connection $connection The connection on which to perform the operation.
	 * @param string $connectionParameters The connection parameters.
	 */
	public function connect(Connection $connection, ConnectionParameters $connectionParameters);

    /**
     * Disconnects from Facebook. Once disconnected, requests cannot be send until the connection
     * has been restored.
     *
	 * @param Connection $connection The connection on which to perform the operation.
     */
	public function disconnect(Connection $connection);
}