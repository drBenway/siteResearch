<?php
/**
 * CSV export command
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */
namespace Crawler\CLI;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Crawler\Export as Export;

/**
 * crawler command to export crawler table to a csv file
 */
class CSVExport extends Console\Command\Command
{
    protected function configure()
    {
        $this
                ->setName('csvexport')
                ->addArgument('csvexport', InputArgument::REQUIRED, 'export content')
                ->setDescription('export content')
                ->setHelp('export the crawler table to a file
                        example: php crawler.php csvexport "test.csv"
                        ');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filepath = $input->getArgument('csvexport');
        $exporter = new Export\CSVExport($filepath);
        $exporter->export();

        $output->writeln("exported results to" . $filepath);
    }

    }
