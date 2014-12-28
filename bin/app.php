#!/usr/bin/env php
<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use PHPWorldWide\FacebookBot\Bot;
use PHPWorldWide\FacebookBot\Connection\ConnectionParameters;

use Symfony\Component\Yaml\Yaml;

// parse parameters.yml file
$yaml = Yaml::parse(file_get_contents('./parameters.yml'));

try {
    $email = $yaml['facebookbot']['email'];
    $password = $yaml['facebookbot']['password'];
    $groupId = $yaml['facebookbot']['group_id'];
    $debug = $yaml['facebookbot']['debug'];

    $connectionParameters = new ConnectionParameters($email, $password, $groupId);

    $bot = new Bot($connectionParameters);
    $bot->getModuleManager()->loadModule('MemberRequest');
} catch (\Exception $e) {
    // Some error occurred
    echo $e->getMessage();
}

