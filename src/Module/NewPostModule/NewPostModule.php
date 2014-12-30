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

use PHPWorldwide\FacebookBot\Connection\Connection;
use PHPWorldwide\FacebookBot\Connection\ConnectionManager;
use PHPWorldwide\FacebookBot\Connection\Request\CURLRequest;

use PHPWorldWide\FacebookBot\Module\ModuleAbstract;

/**
 * A member request handler that automatically approves new membership requests.
 */
class NewPostModule extends ModuleAbstract
{
    const FEED_PATH = '/{group_id}/feed';

    /**
     * Denotes the time of the last read post.
     */
    private $timeCursor;

    /**
     * Creates a new instance.
     *
     * @param ConnectionManager $connectionManager The connectionManager.
     */
    public function __construct(ConnectionManager $connectionManager, $config) 
    {
        parent::__construct($connectionManager, $config);

        $this->timeCursor = 1419863630;
    }

    /**
     * Fetches the list of new posts since the last run.
     *
     * @param Connection $connection The connection to use for requests.
     *
     * @return array The list of new posts entities.
     *
     * @throws ConnectionException If something goes wrong with the connection.
     */
    protected function pollData(Connection $connection)
    {
        $entities = [];
        $params = [ 
            'fields' => 'created_time,message,from',
            'since' => $this->timeCursor,
            'limit' => 100
        ];

        $response = $connection->request(Connection::REQ_GRAPH, self::FEED_PATH, 'GET', $params);
        $posts = $response->getGraphObjectList();
        $postsCount = count($posts);

        foreach ($posts as $post) {
            if ($this->containsCode($post->getProperty('message'))) {
                $entities[] = $this->parseEntity($connection, $post);
            }
        }

        if ($postsCount > 0) {
            $this->updateTimeCursor($posts[0]->getProperty('updated_time'));
        }

        return $entities;
    }

    /**
     * Handles a single post by printing it in stdout.
     *
     * @param Connection $connection The connection to use for requests.
     * @param MemberRequestEntity $entity The entity to handle.
     *
     * @throws ConnectionException If something goes wrong with the connection.
     */
    protected function handleEntity(Connection $connection, $entity)
    {
        if (!$this->isHandled($connection, $entity->getId())) {
            $gistLink = $this->gistifyMessage($entity);
            
            $message = "[admin] Hi, {author}. \nPlease keep your post readable by using Gist as your codepad. We have created an example based on your code, so others can read it clearly: {gist_link}.";

            $message = str_replace('{author}', $entity->getAuthor(), $message);
            $message = str_replace('{gist_link}', $gistLink, $message);

            $data = $entity->getCommentInputData();
            $data['comment_text'] = $message;

            $connection->request(Connection::REQ_LITE, $entity->getCommentActionUrl(), 'POST', $data);
        }
    }

    /**
     * Posts a message to Gist.
     *
     * @param object $entity The post entity.
     *
     * @return string The Gist URL.
     */
    private function gistifyMessage($entity)
    {
        $data = json_encode([
            'public' => true,
            'description' => 'PHPWorldWide: Auto-generated snippet owned by ' . $entity->getAuthor(),
            'files' => [
                'snippet.php' => [ 
                    'content' => $entity->getMessage() 
                ]
            ]
        ]);

        $headers = [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data)
        ];

        $baseUrl = 'https://api.github.com';
        $request = new CURLRequest($baseUrl, '/gists', 'POST', null, $data, false, $headers);
        $response = json_decode($request->execute());

        return $response->html_url;
    }

    private function parseEntity(Connection $connection, $post)
    {
        list($groupId, $postId) = explode('_', $post->getProperty('id'));
        $author = $post->getProperty('from')->getProperty('name');
        $message = $post->getProperty('message');

        $data = [ 
            'view' => 'permalink',
            'id' => $postId
        ];
        
        $page = $connection->request(Connection::REQ_LITE, '/groups/{group_id}', 'GET', $data);
        $dom = new \DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML($page);
        libxml_clear_errors();

        $forms = $dom->getElementsByTagName('form');
        $commentForm = null;

        foreach ($forms as $form) {
            if (strtoupper($form->getAttribute('method')) == 'POST') {
                $commentForm = $form;
            }
        }

        if ($commentForm == null) {
            throw new \Exception("NewPostEntity was not created because the comment form could not be found");
        }

        $commentActionUrl = $commentForm->getAttribute('action');
        $inputElements = $commentForm->getElementsByTagName('input');

        $commentInputData = [ 'submit' => 'Post' ];

        foreach ($inputElements as $input) {
            if ($input->getAttribute('type') != 'submit') {
                $commentInputData[$input->getAttribute('name')] = $input->getAttribute('value');
            }
        }

        return new NewPostEntity($postId, $author, $message, $commentActionUrl, $commentInputData);
    }

    /**
     * Shifts the time cursor to the given date-time in ISO 8601 format.
     *
     * @param string $timeIso8601 The time in ISO 8601 format.
     */
    private function updateTimeCursor($timeIso8601) 
    {
        $dateTime = new \DateTime($timeIso8601);

        $this->timeCursor = $dateTime->getTimestamp();
    }

    /**
     * Scans the comments for admin comments in the past.
     */
    private function isHandled(Connection $connection, $postId) 
    {
        $params = [ 
            'fields' => 'message',
            'limit' => 500
        ];

        $path = "/{group_id}_$postId/comments";
        $response = $connection->request(Connection::REQ_GRAPH, $path, 'GET', $params);
        $comments = $response->getGraphObjectList();

        foreach ($comments as $comment) {
            if (strpos($comment->getProperty('message'), '[admin]') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Scans the message for inline code.
     *
     * @param string $message The message to scan.
     * @param int $minimumLines The minimum amount of subsequent lines that should contain code
     *                          before the condition is met.
     */
    private function containsCode($message, $minimumLines = 3) 
    {
        $messageLines = array_map('trim', explode("\n", $message));
        $linesCount = 0;

        foreach ($messageLines as $messageLine) {
            if ($messageLine == "") {
                continue;
            }

            if ($this->isCodeLine($messageLine)) {
                if (++$linesCount > $minimumLines) {
                    return true;
                }
            } else {
                $linesCount = 0;
            }
        }

        return false;
    }

    /**
     * Determines whether a text line is actually a line of code.
     *
     * @param string $line The line to test.
     *
     * @return boolean True if the line is a line of code, otherwise false.
     */
    private function isCodeLine($line) 
    {
        $patterns = [
            // start PHP code
            '%\<\?(php)?%',

            // variable assignment
            '%\$\w.*\=.*;%',

            // statement
            '%(if|for|foreach|while|switch)\s*\(.*\)%',

            // function call
            '%\w\S*\s*\(.*\)\s*;%',

            // html tag
            '%\<\w+\>(.*</\w+>)%',

            // class field
            '%(public|protected|private)\s+(static\s+)?\$\w.*;%',

            // class function
            '%(public|protected|private)\s+(abstract|static\s+)?function\s*\(%',

            // curly brace
            '%{|}%',

            // class, interface
            '%(abstract\s+)?(class|interface)\s+\w+%',

            // use statement
            '%^use\s+(\S+);%',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line) === 1) {
                return true;
            }
        }

        return false;
    }
}