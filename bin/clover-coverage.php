#!/usr/bin/env php
<?php

use Photogabble\CloverCoverage\CloverCoverageApplication;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../../autoload.php')) {
    require __DIR__ . '/../../../autoload.php';
} else {
    throw new Exception('Unable to find an autoloader. Please run composer install.');
}

$app = new CloverCoverageApplication();
$app->add(new \Photogabble\CloverCoverage\Commands\Analyse());
$app->run();