#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use Lingo\Console\Command\Lingo;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new Lingo());

$application->run();
