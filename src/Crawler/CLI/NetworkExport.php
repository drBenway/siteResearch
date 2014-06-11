<?php

/**
 * Report command
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
 * crawler command to export crawler table to a dot file
 */
class NetworkExport extends Console\Command\Command
{
    /**
     * setup export
     */
    protected function configure()
    {
        $this
                ->setName('network')
                ->addArgument('network', InputArgument::REQUIRED, 'export content')
                ->setDescription('exports netwrok')
                ->setHelp('exports all links between pages in a csv file at a given location.');
    }

    /**
     * add export command
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $servicecontainer = new ContainerBuilder();
        $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__ . '/../config/'));
        $loader->load("dic.xml");

        $filepath = $input->getArgument('network');
        $servicecontainer->setParameter('network.constructorinput', $filepath);
        $exporter = $servicecontainer->get('network');
        $resultdb = $servicecontainer->get('doctrinedb');

        $exporter->export();
        $output->writeln("exported results to" . $filepath);
    }

}
