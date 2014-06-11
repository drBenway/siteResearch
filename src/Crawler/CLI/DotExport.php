<?php

/**
 * Dot export command
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
class DotExport extends Console\Command\Command
{
    /**
     * setup dotexport
     */
    protected function configure()
    {
        $this
                ->setName('dotexport')
                ->addArgument('dotexport', InputArgument::REQUIRED, 'export content')
                ->setDescription('export content to a dot file')
                ->setHelp('
                    export the crawler database to a dot file
                    dot files can be used to generate graphical representations of info\n
                    For more info on what you can do with dot files, see the Graphviz\n
                    application as well as Gephi\n
                        example: php crawler.php dotexport "test.dot"
                        ');
    }

    /**
     * add dotexport command
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $servicecontainer = new ContainerBuilder();
        $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__.'/../config/'));
        $loader->load("dic.xml");

        $filepath = $input->getArgument('dotexport');
        $servicecontainer->setParameter('dotexport.constructorinput', $filepath);

        $exporter = $servicecontainer->get('dotexport');

        $exporter->export();

    }

}
