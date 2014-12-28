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
     * The author of the post.
     */
    private $author;

    /**
     * The message of the post.
     */
    private $message;

    /**
     * The HTTP request URL to issue for commenting.
     */
    private $commentActionUrl;

    /**
     * The HTTP request parameters to issue for commenting.
     */
    private $commentInputData;

    /**
     * Creates a new instance.
     *
     * @param int $id The ID of the post.
     * @param string $author The author of the post.
     * @param string $message The message of the post.
     * @param string $commentActionUrl The HTTP request URL to issue for commenting.
     * @param string $commentInputData The HTTP request parameters to issue for commenting.
     */
    public function __construct($id, $author, $message, $commentActionUrl, $commentInputData)
    {
        $this->id = $id;
        $this->author = $author;
        $this->message = $message;
        $this->commentActionUrl = $commentActionUrl;
        $this->commentInputData = $commentInputData;
    }

    /**
     * Gets the ID of the post.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the author of the post.
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Gets the message of the post.
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Gets the HTTP request URL to issue for commenting.
     */
    public function getCommentActionURL()
    {
        return $this->commentActionUrl;
    }

    /**
     * Gets the HTTP request parameters to issue for commenting.
     */
    public function getCommentInputData()
    {
        return $this->commentInputData;
    }
}