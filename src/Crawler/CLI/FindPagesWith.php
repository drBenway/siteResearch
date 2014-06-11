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
 * returns all pages with a certain string
 */
class FindPagesWith extends Console\Command\Command
{
    /**
     * setup export
     */
    protected function configure()
    {
        $this
                ->setName('findpageswith')
                ->addArgument('findpageswith', InputArgument::REQUIRED, 'export content')
                ->setDescription('returns all pages with a given string')
                ->setHelp('exports a list of pages that contain a given string');
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

        $filepath = $input->getArgument('findpageswith');
        $servicecontainer->setParameter('findpageswith.constructorinput', $filepath);
        $exporter = $servicecontainer->get('findpageswith');
        $exporter->export();
        $output->writeln("exported results to" . $filepath);
    }

}
