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

/**
 * Provides a base for HTTP style request adapters.
 */
abstract class RequestAbstract
{
    /**
     * The request path.
     */
    private $path;
    
    /**
     * The request method.
     */
    private $method;
    
    /**
     * The data to send with the request.
     */
    private $parameters;

    /**
     * Creates a new instance.
     *
     * @param string $path The request path
     * @param string $method The request method
     * @param string $data The data to send with the request
     */
    public function __construct($path, $method, $parameters = []) 
    {
        $this->path = $path;
        $this->method = $method;
        $this->parameters = $parameters;
    }

    public abstract function execute();

    /**
     * Get the request path.
     */
    protected function getPath()
    {
        return $this->path;
    }

    /**
     * Gets the request method.
     */
    protected function getMethod()
    {
        return $this->method;
    }

    /**
     * Gets the data to send with the request.
     */
    protected function getParameters()
    {
        return $this->parameters;
    }
}