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

namespace PHPWorldWide\FacebookBot\Connection\Request;

use Facebook\FacebookRequest;

/**
 * A request adapter for Facebook Graph requests.
 */
class FacebookGraphRequest extends RequestAbstract
{
    /**
     * The session.
     */
    private $session;

    /**
     * Creates a new instance.
     *
     * @param string $path The request path
     * @param string $method The request method
     * @param string $session The session
     * @param string $data The data to send with the request
     */
    public function __construct($path, $method, $session, $data = []) 
    {
        parent::__construct($path, $method, $data);

        $this->session = $session;
    }

    /**
     * Performs the Facebook Graph request using the provided session and returns the result.
     *
     * @return FacebookResponse Result of the request.
     *
     * @throws Exception in case the request has failed.
     */
    public function execute()
    {
        $request = new FacebookRequest(
            $this->session,
            $this->getMethod(),
            $this->getPath(),
            $this->getParameters()
        );

        return $request->execute();
    }
}