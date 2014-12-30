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

namespace PHPWorldWide\FacebookBot\Module\MemberRequestModule;

use PHPWorldWide\FacebookBot\Module\ModuleConfigAbstract;

/**
 * A config class for the Member Request module.
 */
class MemberRequestConfig extends ModuleConfigAbstract
{
    public function __construct($autoload) 
    {
        parent::__construct($autoload);
    }
}