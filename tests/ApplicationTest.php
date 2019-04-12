<?php

namespace Photogabble\CloverCoverage\Tests;

use Photogabble\CloverCoverage\CloverCoverageApplication;
use Photogabble\CloverCoverage\Commands\Analyse;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApplicationTest extends TestCase
{
    public function testApplication()
    {
        $app = new CloverCoverageApplication();
        $app->add(new Analyse());
        $app->setAutoExit(false);
        $applicationTester = new ApplicationTester($app);

        // Expect Error with no xml file defined
        $this->assertEquals(1, $applicationTester->run(['command' => 'analyse']));

        // Expect Error with invalid xml file location
        $this->assertEquals(2, $applicationTester->run(['command' => 'analyse', 'clover-file' => 'not-exist.xml']));

        // Expect Error with invalid xml file
        $this->assertEquals(1, $applicationTester->run(['command' => 'analyse', 'clover-file' => realpath(__DIR__ . DIRECTORY_SEPARATOR . 'broken.xml')]));

        // Expect No Error with valid xml file location
        $this->assertEquals(0, $applicationTester->run(['command' => 'analyse', 'clover-file' => realpath(__DIR__ . DIRECTORY_SEPARATOR . 'clover.xml')]));
    }
}
