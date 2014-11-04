<?php

/*
 * This file is part of the FacebookBot package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wwphp\FacebookBot;

use Wwphp\FacebookBot\Curl;

class Bot
{

    private $curl;

    /**
     * Constructor.
     *
     * @param Curl $curl
     *
     */
    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
    }

    /**
     * Runs the bot.
     *
     * @param Curl $curl
     */
    public function run()
    {
        while (true) {
            $this->curl->approveMember();
        }
    }
}
