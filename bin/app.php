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

    $appId = $yaml['facebookbot']['app_id'];
    $appSecret = $yaml['facebookbot']['app_secret'];
    $accessToken = $yaml['facebookbot']['access_token'];

    $groupId = $yaml['facebookbot']['group_id'];
    $debug = $yaml['facebookbot']['debug'];

    $connectionParameters = new ConnectionParameters($email, $password, $appId, $appSecret, $accessToken, $groupId);

    $bot = new Bot($connectionParameters);
    $bot->getModuleManager()->loadModule('MemberRequest');
    $bot->getModuleManager()->loadModule('NewPost');
} catch (\Exception $e) {
    // Some error occurred
    echo $e->getMessage();
}