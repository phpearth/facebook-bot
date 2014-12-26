# Facebook Bot

[![Build Status](https://secure.travis-ci.org/wwphp-fb/facebook-bot.png?branch=master)](http://travis-ci.org/wwphp-fb/facebook-bot)

![Facebook Bot](bot.png)

Facebook bot for handling some Facebook tasks in the group and a fun project to check what can be automated on Facebook
within Groups specifically.

## Features

* Approving new membership requests

## Installation

```bash
$ git clone git://github.com/wwphp-fb/facebook-bot
$ cd facebook-bot
$ composer install
```

## Usage

For using this bot you can go through the [documentation](docs/index.md) or check simple example below:

```php
<?php

$connection = new Connection('facebook-email@domain.tld', 'password', '314159265');
$bot = new Bot($connection);
$bot->run();
```

## Boring legal stuff

This application is released under the [MIT License](LICENSE).
