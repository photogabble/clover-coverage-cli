<?php

namespace Photogabble\CloverCoverage;

class CloverCoverageApplication extends \Symfony\Component\Console\Application
{
    public function __construct($name = 'Clover Coverage Cli', $version = '1.0.0')
    {
        parent::__construct($name, $version);
    }
}