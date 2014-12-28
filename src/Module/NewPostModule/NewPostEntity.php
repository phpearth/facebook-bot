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

namespace PHPWorldWide\FacebookBot\Module\NewPostModule;

/**
 * A data bag for new posts.
 */
class NewPostEntity
{
    /**
     * The ID of the post.
     */
    private $id;

    /**
     * The message of the post.
     */
    private $message;

    /**
     * Creates a new instance.
     *
     * @param int $id The ID of the post.
     * @param string $message The message of the post.
     */
    public function __construct($id, $message)
    {
        $this->id = $id;
        $this->message = $message;
    }

    /**
     * Gets the ID of the post.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the message of the post.
     */
    public function getMessage()
    {
        return $this->message;
    }
}