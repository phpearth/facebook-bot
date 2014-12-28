<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Peter Kokot
 * @author  Dennis Degryse
 * @since   0.0.4
 * @version 0.0.4
 */

namespace PHPWorldWide\FacebookBot\Connection\Request;

/**
 * A request adapter for cURL HTTP requests.
 */
class CURLRequest extends RequestAbstract
{
    const USERAGENT = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040803 Firefox/0.9.3';

    /**
     * The base url for the request.
     */
    private $baseUrl;

    /**
     * The session cookies.
     */
    private $cookies;

    /**
     * Whether or not to retrieve only headers (exclusive).
     */
    private $headersOnly;

    /**
     * Creates a new instance.
     *
     * @param string $baseUrl The base url for the request
     * @param string $path The request path
     * @param string $method The request method
     * @param string $cookies The session cookies
     * @param string $data The data to send with the request
     * @param string $headersOnly Whether or not to retrieve only headers (exclusive)
     */
    public function __construct($baseUrl, $path, $method, $cookies = null, $data = [], $headersOnly = false) 
    {
        parent::__construct($path, $method, $data);

        $this->baseUrl = $baseUrl;
        $this->cookies = $cookies;
        $this->headersOnly = $headersOnly;
    }

    /**
     * Performs the HTTP request with cURL using the provided cookie and returns the result.
     *
     * @return string Result of cURL session.
     *
     * @throws Exception in case the cURL request has failed.
     */
    public function execute()
    {
        $curl = curl_init();

        $url = $this->baseUrl . $this->getPath();

        curl_setopt($curl, CURLOPT_HEADER, $this->headersOnly);
        curl_setopt($curl, CURLOPT_NOBODY, $this->headersOnly);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_COOKIE, $this->cookies);
        curl_setopt($curl, CURLOPT_USERAGENT, self::USERAGENT);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        if (count($this->getParameters()) > 0) {
            $dataString = http_build_query($this->getParameters());
        } else {
            $dataString = "";
        }

        switch ($this->getMethod()) {
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
                break;
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