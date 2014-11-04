<?php

/*
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PHPWorldWide\FacebookBot;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 * Curl
 */
class Curl
{
    public $email;
    public $password;
    private $debug;

    /**
     * in case you have location checking turned on
     */
    private $deviceName = 'Home';

    private $uagent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040803 Firefox/0.9.3';
    private $cookies = '';
    private $logger;

    /**
     * Constructor
     *
     * @param string $email Email to login to Facebook account
     * @param string $password Password to login to Facebook account
     * @param boolean $debug Set debuging on or off
     *
     */
    public function __construct($email, $password, $debug = false)
    {
        $this->email = $email;
        $this->password = $password;
        $this->debug = $debug;
        $this->logger = new Logger('curl');
        $this->logger->pushHandler(new StreamHandler(__DIR__.'/../logs/curl.log', Logger::DEBUG));
    }

    /**
     * Opens URL with curl and performs curl session.
     *
     * @param string $url URL to open
     * @param string $header Headers for curl
     * @param string $cookies cookies for curl
     * @param string $postData Data to send in curl
     *
     * @return string|false Result of curl session or false in case of failure.
     */
    public function executeUrl($url, $header = null, $cookies = null, $postData = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_NOBODY, $header);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_COOKIE, $cookies);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->uagent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        if ($postData) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }
        $result = curl_exec($ch);

        if ($result) {
            return $result;
        } else {
            return curl_error($ch);
        }
        curl_close($ch);
    }

    /**
     * Login to Facebook.
     */
    public function login()
    {
        $this->cookies = "";
        $a = $this->executeUrl("https://login.facebook.com/login.php?login_attempt=1",true,null,"email=$this->email&pass=$this->password");
        preg_match('%Set-Cookie: ([^;]+);%',$a,$b);
        $c = $this->executeUrl("https://login.facebook.com/login.php?login_attempt=1",true,$b[1],"email=$this->email&pass=$this->password");
        preg_match_all('%Set-Cookie: ([^;]+);%',$c,$d);
        for ($i=0;$i<count($d[0]);$i++) {
            $this->cookies.=$d[1][$i].";";
        }
    }

    /**
     * Approve member.
     */
    public function approveMember()
    {
        $page = $this->executeUrl('http://m.facebook.com/groups/2204685680/?view=members', null, $this->cookies, null);
        $inputs = $this->parseInputs($page);
        $postParams = '';
        $counter = 0;
        foreach ($inputs as $input) {
            if ($input->getAttribute('name') == 'fb_dtsg' || $input->getAttribute('name') == 'charset_test') {
                $postParams .= $input->getAttribute('name') . '=' . urlencode($input->getAttribute('value')) . '&';
                $counter ++;
            }
            if ($counter == 2){
                break;
            }
        }
        $postParams .= 'confirm=Add';
        $formAction = $this->parseAction($page, 1);
        if ($this->debug) {
            $this->logger->addInfo('Approving member');
            $this->logger->addInfo('formAction: ' . $formAction);
            $this->logger->addInfo('postParams: ' . $postParams);
        }

        //approve member
        $approvedPage = $this->executeUrl($formAction, null, $this->cookies, $postParams);
    }

    /**
     * Parses all inputs of given HTML.
     *
     * @param string $html HTML string to parse
     * @return array Array of Form input field names & values.
     */
    public function parseInputs($html)
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
    public function parseAction($html, $whichNum = 0) {
        $dom = new \DOMDocument;
        $dom->loadHTML($html);
        $formAction = $dom->getElementsByTagName('form')->item($whichNum)->getAttribute('action');
        if (!strpos($formAction, "//")) {
            $formAction = 'https://m.facebook.com'.$formAction;
        }
        return($formAction);
    }

}
