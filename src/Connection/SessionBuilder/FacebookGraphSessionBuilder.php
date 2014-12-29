<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.4
 * @version 0.0.4
 */

namespace PHPWorldWide\FacebookBot\Connection\SessionBuilder;

use Facebook\FacebookSession;

/**
 * An adapter for Facebook Graph session builders.
 */
class FacebookGraphSessionBuilder implements SessionBuilder
{
    /**
     * The access token.
     */
    private $accessToken;

    /**
     * Creates a new instance.
     *
     * @param string $accesstoken The access token.
     */
    public function __construct($appId, $appSecret, $accessToken)
    {
        FacebookSession::setDefaultApplication($appId, $appSecret);

        $this->accessToken = $accessToken;
    }

    public function build()
    {
        if ($this->accessToken != null) {
            $session = new FacebookSession($this->accessToken);
        } else {
            $session = FacebookSession::newAppSession();
        }

        return $session;
    }
}