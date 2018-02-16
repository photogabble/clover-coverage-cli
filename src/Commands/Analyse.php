<?php

namespace Photogabble\CloverCoverage\Commands;

use Photogabble\CloverCoverage\Analyser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Analyse extends Command
{
    /**
     * Configures the current command.
     */
    public function configure()
    {
        $this->setName('analyse')
            ->setDescription('Analyses clover.xml and outputs coverage information broken down by file');

        $this->addArgument('clover-file', InputArgument::REQUIRED, 'Path to valid clover.xml file');
        $this->addOption('summary', 's', InputOption::VALUE_NONE, 'Only show total coverage');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $analyser = new Analyser($input->getArgument('clover-file'));
            $analyser->analyse();

            if (count($analyser->getAnalysis()->files) < 1) {
                $output->writeln('<error>[!]</error> The input file does not appear to contain any usable data.');
                return 1;
            }

            if ($input->getOption('summary') === false ){
                $table = $analyser->getTable($output);
                $table->render();
            }

            $output->writeln('Code Coverage: ' . number_format($analyser->getCoveragePercentage(), 2) . '%');
        } catch (\Exception $e) {
            $output->writeln('<error>[!]</error> ' . $e->getMessage());
            return 2;
        }

        return 0;
    }

}