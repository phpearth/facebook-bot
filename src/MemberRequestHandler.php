<?php

/**
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author  Peter Kokot 
 * @author  Dennis Degryse
 * @since   0.0.2
 * @version 0.0.2
 */

namespace PHPWorldwide\FacebookBot;

use PHPWorldwide\FacebookBot\Connection\Connection;

/**
 * A member request handler that automatically approves new membership requests.
 */
class MemberRequestHandler 
{
	const MEMBERLIST_URL = 'http://m.facebook.com/groups/$this->group_id/?view=members';

    /**
     * Fetches a list of membership requests and approves each one of them.
     *
     * @param Connection $connection The connection to use for requests.
     *
     * @throws ConnectionException If something goes wrong with the connection.
     */
	public void run(Connection $connection) 
	{
        $page = $connection->request(MEMBERLIST_URL, "GET");

        if (strpos($page, '<h4 class="bb j">Requests</h4>') === false) 
        {
            return;
        }

        $inputs = $this->parseInputs($page);
        $postParams = [];
        $counter = 0;
        
        foreach ($inputs as $input) {
            if ($input->getAttribute('name') == 'fb_dtsg' || $input->getAttribute('name') == 'charset_test') 
            {
                $postParams[$input->getAttribute('name')] = $input->getAttribute('value');
                $counter ++;
            }
        
            if ($counter == 2)
            {
                break;
            }
        }
        
        $postParams['confirm'] = 'Add';
        $formAction = $this->parseAction($page, 1);
        
        if ($this->debug) {
            $this->logger->addInfo('Approving member');
            $this->logger->addInfo('formAction: ' . $formAction);
            $this->logger->addInfo('postParams: ' . $postParams);
        }

        //approve member
        $approvedPage = $connection->request($formAction, "POST", $postParams);
    }

    /**
     * Parses all inputs of given HTML.
     *
     * @param string $html HTML string to parse
     * @return array Array of Form input field names & values.
     */
    private function parseInputs($html)
    {
        $dom = new \DOMDocument;
        
        $dom->loadHTML($html);
        $inputs = $dom->getElementsByTagName('input');
        
        return($inputs);
    }

    /**
     * Parses form to get action URL.
     *
     * @param string $html HTML string to parse.
     * @param int $whichNum number of a form in HTML string.
     *
     * @return string URL of the form's action
     */
    private function parseAction($html, $whichNum = 0) {
        $dom = new \DOMDocument;
        
        $dom->loadHTML($html);
        $formAction = $dom->getElementsByTagName('form')->item($whichNum)->getAttribute('action');
        
        if (!strpos($formAction, "//")) {
            $formAction = 'https://m.facebook.com'.$formAction;
        }
        
        return($formAction);
    }
}