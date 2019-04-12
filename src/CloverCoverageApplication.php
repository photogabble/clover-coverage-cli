<?php

namespace Photogabble\CloverCoverage;

use Symfony\Component\Console\Application;

class CloverCoverageApplication extends Application
{
    public function __construct($name = 'Clover Coverage Cli', $version = '1.1.0')
    {
        parent::__construct($name, $version);
    }
}
