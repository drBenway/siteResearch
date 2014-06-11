<?php

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
 * crawler command to export results as a sitemap file
 */
class SitemapExport extends Console\Command\Command
{
    protected function configure()
    {
        $this
                ->setName('sitemapexport')
                ->addArgument('sitemapexport', InputArgument::REQUIRED, 'export content')
                ->setDescription('export result to a sitemap file')
                ->setHelp('export sitemap file
                            example: php crawler.php sitemapexport "test.xml";
                            ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $servicecontainer = new ContainerBuilder();
        $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__.'/../config/'));
        $loader->load("dic.xml");

        $filepath = $input->getArgument('sitemapexport');
        $servicecontainer->setParameter('sitemapexport.constructorinput', $filepath);
        $exporter = $servicecontainer->get('sitemapexport');
        $exporter->export();
    }

}
