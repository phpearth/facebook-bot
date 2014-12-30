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

namespace PHPWorldWide\FacebookBot\Module\NewPostModule;

use PHPWorldWide\FacebookBot\Module\ModuleConfigAbstract;

/**
 * A config class for the New Post module.
 */
class NewPostConfig extends ModuleConfigAbstract
{
    private $gistifyComment;
    private $gistifyMinimumLines;
    private $gistifyPatterns;

    public function __construct($autoload, $gistifyComment, $gistifyMinimumLines, $gistifyPatterns) 
    {
        parent::__construct($autoload);

        $this->gistifyComment = $gistifyComment;
        $this->gistifyMinimumLines = $gistifyMinimumLines;
        $this->gistifyPatterns = $gistifyPatterns;
    }

    public function getGistifyComment()
    {
        return $this->gistifyComment;
    }

    public function getGistifyMinimumLines()
    {
        return $this->gistifyMinimumLines;
    }

    public function getGistifyPatterns()
    {
        return $this->gistifyPatterns;
    }
}