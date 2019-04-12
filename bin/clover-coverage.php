#!/usr/bin/env php
<?php

use Photogabble\CloverCoverage\CloverCoverageApplication;
use Photogabble\CloverCoverage\Commands\Analyse;

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    echo 'Cannot find the vendor directory, have you executed composer install?' . PHP_EOL;
    echo 'See https://getcomposer.org to get Composer.' . PHP_EOL;
    exit(1);
}

$app = new CloverCoverageApplication();
$app->add(new Analyse());

$app->run();
