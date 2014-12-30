<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Dennis Degryse
 * @since   0.0.5
 * @version 0.0.5
 */

namespace PHPWorldWide\FacebookBot\Module;

/**
 * Provides a base config class for modules.
 */
abstract class ModuleConfigAbstract
{
    /**
     * Whether to load the module at startup.
     */
    private $autoload;

    /**
     * Creates a new instance
     *
     * @param $autoload Whether to load the module at startup.
     */
    public function __construct($autoload) 
    {
        $this->autoload = $autoload;
    }

    /**
     * Gets whether to load the module at startup.
     */
    public function isAutoload() 
    {
        return $this->autoload;
    }
}