#!/usr/bin/env php
<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use PHPWorldWide\FacebookBot\Connection\Connection;
use PHPWorldWide\FacebookBot\Bot;
use Symfony\Component\Yaml\Yaml;

// parse parameters.yml file
$yaml = Yaml::parse(file_get_contents('./parameters.yml'));

try {
    $email = $yaml['facebookbot']['email'];
    $password = $yaml['facebookbot']['password'];
    $group_id = $yaml['facebookbot']['group_id'];
    $debug = $yaml['facebookbot']['debug'];

    $connection = new Connection($email, $password, $group_id, $debug);
    $connection->connect();
    $bot = new Bot($connection);
    $bot->run();
} catch (\Exception $e) {
    // Some error occurred
    echo $e->getMessage();
}

