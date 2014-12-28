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
 * Provides an abstract class for connection states.
 */
abstract class ConnectionStateAbstract implements ConnectionState
{
    const USERAGENT = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040803 Firefox/0.9.3';

	public abstract function request(Connection $connection, $url, $method, $data);

	public abstract function connect(Connection $connection, ConnectionParameters $connectionParameters);

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
	protected function doCurlRequest($url, $method, $data = [], $header = null, $cookies = null)
    {
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_HEADER, $header);
        curl_setopt($curl, CURLOPT_NOBODY, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_COOKIE, $cookies);
        curl_setopt($curl, CURLOPT_USERAGENT, self::USERAGENT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        if (count($data) > 0) {
            $dataString = http_build_query($data);
        } else {
            $dataString = "";
        }

        switch ($method) {
            case 'POST':
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $dataString);
                break;

            case 'GET': 
                curl_setopt($curl, CURLOPT_URL, "$url?$dataString");
                break;

            default:
                throw new \Exception("An error has occured during the cURL request: Method $method is currently not supported");
        }

        $result = curl_exec($curl);

        if (!$result) 
        {
            $errorDetails = curl_error($curl);

            throw new \Exception("An error has occured during the cURL request: " . $errorDetails);
        }

        curl_close($curl);

        return $result;
    }
}