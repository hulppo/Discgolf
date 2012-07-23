#!/usr/bin/env php
<?php

// if you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

set_time_limit(0);

require_once __DIR__.'/bootstrap.php.cache';
require_once __DIR__.'/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;

$kernel = new AppKernel('dev', true);

$app = new Application($kernel);
$app->setCatchExceptions(false);

$input = new StringInput('discgolf:parsemaildir tmp/');

$app->run($input);