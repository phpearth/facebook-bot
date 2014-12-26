<?php

namespace PHPWorldwide\FacebookBot\Connection;

/**
 * Provides an interface for connection states.
 *
 * @author  Dennis Degryse [dennisdegryse@gmail.com]
 * @since   0.0.2
 * @version 0.0.2
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
	public function request(Connection $connection, string $url, string $method, array $data);

	/**
	 * Connects to Facebook using the stored credentials. Once connected, requests can be sent.
	 *
	 * @param Connection $connection The connection on which to perform the operation.
	 * @param string $email The login email
	 * @param string $password The login password
	 */
	public function connect(Connection $connection, string $email, string $password);

    /**
     * Disconnects from Facebook. Once disconnected, requests cannot be send until the connection
     * has been restored.
     *
	 * @param Connection $connection The connection on which to perform the operation.
     */
	public function disconnect(Connection $connection);
}