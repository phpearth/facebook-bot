#!/usr/bin/env php
<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/FacebookBot/Curl.php';

use FacebookBot\Curl;
use Symfony\Component\Yaml\Yaml;

// parse parameters.yml file
$yaml = Yaml::parse(file_get_contents('./parameters.yml'));

try {
    $email = $yaml['facebookbot']['email'];
    $password = $yaml['facebookbot']['password'];
    $debug = $yaml['facebookbot']['debug'];

    $curl = new Curl($email, $password, $debug);
    $curl->login();
    $curl->logout();
} catch (\Exception $e) {
    // Some error occurred
    echo $e->getMessage();
}

