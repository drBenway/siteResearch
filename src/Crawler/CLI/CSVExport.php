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
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Crawler\Export as Export;

/**
 * crawler command to export crawler table to a csv file
 */
class CSVExport extends Console\Command\Command
{
    /**
     * setup csvexport
     */
    protected function configure()
    {
        $this
                ->setName('csvexport')
                ->addArgument('csvexport', InputArgument::REQUIRED, 'export content')
                ->setDescription('export result to csv file')
                ->setHelp('
                    export the crawler database to a csv file (can be used for example in Excel)\n
                    example: php crawler.php csvexport "test.csv"
                    ');

    }

    /**
     * add csvexport command
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $servicecontainer = new ContainerBuilder();
        $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__.'/../config/'));
        $loader->load("dic.xml");

        $filepath = $input->getArgument('csvexport');
        $servicecontainer->setParameter('csvexport.constructorinput', $filepath);
        $exporter = $servicecontainer->get('csvexport');
        $resultdb = $servicecontainer->get('newresultdb');
        $exporter->setDB($resultdb);
        $exporter->export();

    }

    }
