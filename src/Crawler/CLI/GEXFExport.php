<?php

/**
 * GEXFE export command
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
 * crawler command to export crawler table to a gexf file
 */
class GEXFExport extends Console\Command\Command
{
    protected function configure()
    {
        $this
                ->setName('gexfexport')
                ->addArgument('gexfexport', InputArgument::REQUIRED, 'export content')
                ->setDescription('export content to GEXF format (Gephi)')
                ->setHelp('
                    export the crawler database to a gexf file\n
                    example: php crawler.php gexfexport "test.gexf"
                    ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $servicecontainer = new ContainerBuilder();
        $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__.'/../config/'));
        $loader->load("dic.xml");

        $filepath = $input->getArgument('gexfexport');
        $servicecontainer->setParameter('gexfexport.constructorinput', $filepath);
        $resultdb = $servicecontainer->get('newresultdb');
        $exporter = $servicecontainer->get('gexfexport');
        $exporter->export();
    }

}
