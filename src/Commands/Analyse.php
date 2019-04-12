<?php

namespace Photogabble\CloverCoverage\Commands;

use Exception;
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
            ->setDescription('Analyses clover.xml and outputs coverage information broken down by file')
            ->addArgument('clover-file', InputArgument::REQUIRED, 'Path to valid clover.xml file')
            ->addOption('failure-percentage', 'f', InputOption::VALUE_OPTIONAL, 'Threshold below which files are marked as failed', 0)
            ->addOption('warning-percentage', 'w', InputOption::VALUE_OPTIONAL, 'Threshold below which files are marked as warning', 90)
            ->addOption('error-percentage', 'e', InputOption::VALUE_OPTIONAL, 'Threshold below which files are marked as error', 80)
            ->addOption('exit', null, InputOption::VALUE_OPTIONAL, 'Exit with error if overall coverage is equal to or less than failure percentage.')
            ->addOption('summary', 's', InputOption::VALUE_NONE, 'Only show total coverage');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $analyser = new Analyser(
                $input->getArgument('clover-file'),
                $input->getOption('warning-percentage'),
                $input->getOption('error-percentage'),
                $input->getOption('failure-percentage')
            );

            $analyser->analyse();

            if (count($analyser->getAnalysis()->files) < 1) {
                $output->writeln('<error>[!]</error> The input file does not appear to contain any usable data.');
                return 1;
            }

            if ($input->getOption('summary') === false) {
                $table = $analyser->getTable($output);
                $table->render();
            }

            $percentage = $analyser->getCoveragePercentage();
            $output->writeln('Code Coverage: ' . number_format($percentage, 2) . '%');

            if ($exit = $input->getOption('exit')) {
                if ($percentage <= $exit) {
                    return 1;
                }
            }

        } catch (Exception $e) {
            $output->writeln('<error>[!]</error> ' . $e->getMessage());
            return 2;
        }

        return 0;
    }

}
