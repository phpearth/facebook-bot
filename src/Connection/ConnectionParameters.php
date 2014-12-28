<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.3
 * @version 0.0.4
 */

namespace PHPWorldWide\FacebookBot\Connection;

/**
 * An entity containing all connection parameters.
 */
class ConnectionParameters 
{
    /**
     * The login e-mail.
     */
    private $email;

    /**
     * The login password.
     */
    private $password;

    /**
     * The Facebook App ID.
     */
    private $appId;

    /**
     * The Facebook App Secret key.
     */
    private $appSecret;

    /**
     * The Facebook Graph access token.
     */
    private $accessToken;

    /**
     * The group's id.
     */
    private $groupId;

    /**
     * Creates a new instance.
     *
     * @param string $email The login e-mail
     * @param string $password The login password
     * @param string $appId The Facebook App ID
     * @param string $appSecret Facebook App Secret key
     * @param string $accessToken The Facebook Graph access token
     * @param string $groupId The group's id
     */
    public function __construct($email, $password, $appId, $appSecret, $accessToken, $groupId)
    {
        $this->email = $email;
        $this->password = $password;
        $this->appId = $appId;
        $this->appSecret = $appSecret;
        $this->accessToken = $accessToken;
        $this->groupId = $groupId;
    }

    /**
     * Gets the login e-mail.
     *
     * @return string The login e-mail
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Gets the login password.
     *
     * @return string The login password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Gets the Facebook App ID.
     *
     * @return string The Facebook App ID.
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Gets the Facebook App Secret key.
     *
     * @return string The Facebook App Secret key.
     */
    public function getAppSecret()
    {
        return $this->appSecret;
    }

    /**
     * Gets the Facebook Graph access token.
     *
     * @return string The access token
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Gets the group's id.
     *
     * @return string The group's id
     */
    public function getGroupId()
    {
        return $this->groupId;
    }
}