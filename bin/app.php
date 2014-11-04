#!/usr/bin/env php
<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use PHPWorldWide\FacebookBot\Curl;
use PHPWorldWide\FacebookBot\Bot;
use Symfony\Component\Yaml\Yaml;

// parse parameters.yml file
$yaml = Yaml::parse(file_get_contents('./parameters.yml'));

try {
    $email = $yaml['facebookbot']['email'];
    $password = $yaml['facebookbot']['password'];
    $debug = $yaml['facebookbot']['debug'];

    $curl = new Curl($email, $password, $debug);
    $curl->login();
    $bot = new Bot($curl);
    $bot->run();
} catch (\Exception $e) {
    // Some error occurred
    echo $e->getMessage();
}

