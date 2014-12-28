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

namespace PHPWorldWide\FacebookBot\Module\MemberRequestModule;

/**
 * A data bag for membership requests.
 */
class MemberRequestEntity
{
    /**
     * The name of the member.
     */
    private $name;

    /**
     * The Facebook profile URL of the member.
     */
    private $profileUrl;

    /**
     * The HTTP request URL to issue for approval.
     */
    private $actionUrl;

    /**
     * The HTTP request parameters to issue for approval.
     */
    private $inputData;

    /**
     * Creates a new instance.
     *
     * @param string $name The name of the member.
     * @param string $profileUrl The Facebook profile URL of the member.
     * @param string $actionUrl The HTTP request URL to issue for approval.
     * @param string $inputData The HTTP request parameters to issue for approval.
     */
    public function __construct($name, $profileUrl, $actionUrl, $inputData)
    {
        $this->name = $name;
        $this->profileUrl = $profileUrl;
        $this->actionUrl = $actionUrl;
        $this->inputData = $inputData;
    }

    /**
     * Gets the name of the member.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the Facebook profile URL of the member.
     */
    public function getProfileURL()
    {
        return $this->profileUrl;
    }

    /**
     * Gets the HTTP request URL to issue for approval.
     */
    public function getActionURL()
    {
        return $this->actionUrl;
    }

    /**
     * Gets the HTTP request parameters to issue for approval.
     */
    public function getInputData()
    {
        return $this->inputData;
    }
}