<?php
/**
 * Calcultate the pagerank
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */
namespace SiteParser\CLI;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\INputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    SiteParser\Extractors as Extractors,
    Crawler\Classes as Crawler;

/**
 * crawler command to export crawler table to a csv file
 */
class NetworkExport extends Console\Command\Command
{
    /**
     * setup calculator
     */
    protected function configure()
    {
       $this
                ->setName('GetNetwork')

                ->setDescription('extracts all the links found in a crawled site ')
                ->setHelp('After you have run php crawler.php do
                "php siteresearch.php GetNetwork". This will draw all the links into a table for further analysis.');
    }

    /*
     * execute pagerank calculator
     */

    protected function execute(InputInterface $input, OutputInterface $output)
    {
         $servicecontainer = new ContainerBuilder();
         $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__.'/../config/'));
         $loader->load("dic.xml");

        $db = $servicecontainer->get('LinksFromPagesDB');
        $pageparser = new Crawler\Classes\PageCrawler();
    }

    }
