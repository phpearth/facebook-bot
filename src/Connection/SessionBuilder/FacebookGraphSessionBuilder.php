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
    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function build()
    {
        // FacebookSession::setDefaultApplication('app-id', 'app-secret');

        return new FacebookSession($this->accessToken);
    }
}