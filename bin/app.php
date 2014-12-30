#!/usr/bin/env php
<?php

$loader = require_once __DIR__ . '/../vendor/autoload.php';

use PHPWorldWide\FacebookBot\Bot;
use PHPWorldWide\FacebookBot\Config\ConfigReader;

try {
    $config = new ConfigReader('./parameters.yml');

    $bot = new Bot($config);
} catch (\Exception $e) {
    // Some error occurred
    echo $e->getMessage();
}