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
 * Provides an abstract class for connection states.
 */
abstract class ConnectionStateAbstract implements ConnectionState
{
    const USERAGENT = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040803 Firefox/0.9.3';

	public abstract function request(Connection $connection, string $url, string $method, array $data);

	public abstract function connect(Connection $connection, string $email, string $password);

	public abstract function disconnect(Connection $connection);

    /**
     * Opens URL with curl and performs curl session.
     *
     * @param string $url URL to open
     * @param string $header Headers for curl
     * @param string $cookies cookies for curl
     * @param string $postData Data to send in curl
     *
     * @return string Result of cURL session.
     *
     * @throws Exception in case the cURL request has failed.
     */
	protected function doCurlRequest(string $url, string $method, array $data = [], string $header = null, string $cookies = null)
    {
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_HEADER, $header);
        curl_setopt($curl, CURLOPT_NOBODY, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_COOKIE, $cookies);
        curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        $dataString = http_build_query($data);

        if ($method == "POST")
        {
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
        }
        elseif ($method == "GET") 
        {
            curl_setopt($curl, CURLOPT_URL, "$url?$dataString");
        }

        $result = curl_exec($curl);

        if (!$result) 
        {
            throw new \Exception("An error has occured during the cURL request: " . curl_error($curl));
        }

        curl_close($curl);

        return $result;
    }
}