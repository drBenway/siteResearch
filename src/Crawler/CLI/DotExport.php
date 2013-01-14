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
class DotExport extends Console\Command\Command
{
    protected function configure()
    {
        $this
                ->setName('dotexport')
                ->addArgument('dotexport', InputArgument::REQUIRED, 'export content')
                ->setDescription('export content')
                ->setHelp('
                    export the crawler database to a dot file
                    dot files can be used to generate graphical representations of info\n
                    For more info on what you can do with dot files, see the Graphviz\n 
                    application as well as Gephi\n
                        example: php crawler.php dotexport "test.dot"
                        ');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filepath = $input->getArgument('dotexport');
        $exporter = new Export\DotExport($filepath);
        $exporter->export();

        $output->writeln("exported results to" . $filepath);
    }

    }
