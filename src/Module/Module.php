<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.3
 * @version 0.0.3
 */

namespace PHPWorldWide\FacebookBot\Module;

interface Module 
{
    /**
     * Starts the module.
     */
    public function start();

    /**
     * Stops the module.
     */
    public function stop();
}