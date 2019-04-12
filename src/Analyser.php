<?php

namespace Photogabble\CloverCoverage;

use Exception;
use SimpleXMLElement;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class Analyser
{
    /**
     * @var string
     */
    private $filePathname;

    /**
     * @var int
     */
    private $warningPercentage;

    /**
     * @var int
     */
    private $errorPercentage;

    /**
     * @var int
     */
    private $failurePercentage;

    /**
     * @var Analysis
     */
    private $analysis;

    /**
     * Analyser constructor.
     *
     * @param string $filePathname
     * @param int $warningPercentage
     * @param int $errorPercentage
     * @param int $failurePercentage
     *
     * @throws Exception
     */
    public function __construct($filePathname, $warningPercentage = 90, $errorPercentage = 80, $failurePercentage = 0)
    {
        if (!file_exists($filePathname)) {
            throw new Exception('No file could be read at path [' . $filePathname . ']');
        }

        $this->filePathname = $filePathname;
        $this->warningPercentage = $warningPercentage;
        $this->errorPercentage = $errorPercentage;
        $this->failurePercentage = $failurePercentage;
    }

    /**
     * Analyse the input file and build Analysis class.
     *
     * @return void
     */
    public function analyse()
    {
        $cloverXml = new SimpleXMLElement($this->filePathname, null, true);
        $analysis = new Analysis();

        foreach ($cloverXml->xpath('//file') as $file) {
            $analysis->addFile((string)$file['name'], new Analysis(
                (int)$file->metrics['loc'],
                (int)$file->metrics['ncloc'],
                (int)$file->metrics['classes'],
                (int)$file->metrics['methods'],
                (int)$file->metrics['coveredmethods'],
                (int)$file->metrics['conditionals'],
                (int)$file->metrics['coveredconditionals'],
                (int)$file->metrics['statements'],
                (int)$file->metrics['coveredstatements'],
                (int)$file->metrics['elements'],
                (int)$file->metrics['coveredelements']
            ));
        }

        $this->analysis = $analysis;
    }

    /**
     * @return Analysis
     */
    public function getAnalysis()
    {
        return $this->analysis;
    }

    /**
     * @return float
     */
    public function getCoveragePercentage()
    {
        return $this->analysis->getCoveragePercentage();
    }

    /**
     * @param OutputInterface $output
     * @return Table
     */
    public function getTable(OutputInterface $output)
    {
        $table = new Table($output);
        $table->setStyle('borderless');
        $table->setHeaders(['File', 'Coverage']);
        foreach ($this->analysis->files as $name => $file) {
            $percentage = $file->getCoveragePercentage();
            if ($percentage < $this->errorPercentage) {
                $icon = '!';
            } elseif ($percentage < $this->warningPercentage) {
                $icon = '-';
            } else {
                $icon = '✓';
            }

            $table->addRow([$name, str_pad(number_format($percentage, 2) . '%', 7, ' ') . ' ' . $icon]);
        }
        return $table;
    }
}
