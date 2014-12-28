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

namespace PHPWorldWide\FacebookBot\Connection\SessionBuilder;

use PHPWorldWide\FacebookBot\Connection\Request\CURLRequest;

/**
 * An adapter for Facebook Graph session builders.
 */
class FacebookSessionBuilder implements SessionBuilder
{
    const REQ_BASEURL = 'https://login.facebook.com';
    const REQ_PATH = '/login.php?login_attempt=1';

    /**
     * The login e-mail.
     */
    private $email;

    /**
     * The login password.
     */
    private $password;

    /**
     * Creates a new instance.
     *
     * @param string $email The login e-mail.
     * @param string $password The login password.
     */
    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function build()
    {
        $data = [ 
            'email' => $connectionParameters->getEmail(), 
            'pass' => $connectionParameters->getPassword() ];

        $request = new CURLRequest(self::REQ_BASEURL, self::REQ_PATH, 'POST', null, $data, true);
        $result = $request->execute();
        preg_match('%Set-Cookie: ([^;]+);%', $result, $cookieData);
        $cookies = $cookieData[1];

        $request = new CURLRequest(self::REQ_BASEURL, self::REQ_PATH, 'POST', $cookies, $data, true);
        $result = $request->execute();
        preg_match_all('%Set-Cookie: ([^;]+);%', $result, $cookieData);
        $cookies = implode(';', $cookieData[1]);

        return $cookies;
    }
}