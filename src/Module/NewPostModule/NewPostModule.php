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

use PHPWorldWide\FacebookBot\Module\ModuleAbstract;

/**
 * A member request handler that automatically approves new membership requests.
 */
class NewPostModule extends ModuleAbstract
{
    const FEED_PATH = '/{group_id}/feed';

    private $timeCursor;

    public function __construct(ConnectionManager $connectionManager) 
    {
        parent::__construct($connectionManager);

        $this->timeCursor = time();
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
            'fields' => 'created_time,message',
            'since' => $this->timeCursor,
            'limit' => 100
        ];

        $response = $connection->request(Connection::REQ_GRAPH, self::FEED_PATH, 'GET', $params);
        $posts = $response->getGraphObjectList();
        $postsCount = count($posts);

        foreach ($posts as $post) {
            if ($this->containsCode($post->getProperty('message'))) {
                $entities[] = new NewPostEntity($post->getProperty('id'), $post->getProperty('message'));
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
        echo 'Post with id ' . $entity->getId() . " has tested positive for inline code: \n";// . $entity->getMessage() . "\n\n";
    }

    private function updateTimeCursor($postTimeIso) 
    {
        $dateTime = new \DateTime($postTimeIso);

        $this->timeCursor = $dateTime->getTimestamp();
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
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $line) === 1) {
                return true;
            }
        }

        return false;
    }
}