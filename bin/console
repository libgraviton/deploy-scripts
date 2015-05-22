#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Graviton\Deployment\Command\CloudFoundry\AuthCommand;
use Graviton\Deployment\Command\CloudFoundry\CheckApplicationCommand;
use Graviton\Deployment\Command\CloudFoundry\LoginCommand;
use Graviton\Deployment\Command\CloudFoundry\LogoutCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new CheckApplicationCommand());
$application->add(new LoginCommand());
$application->add(new LogoutCommand());
$application->add(new AuthCommand());
$application->run();
