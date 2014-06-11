<?php
/**
 * Import from url command
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
    Crawler\Classes as Classes;

/**
 * crawler starts from a given url
 */
class FromUrl extends Console\Command\Command
{
    protected function configure()
    {
        $this
                ->setName('fromurl')
                ->addArgument('fromurl', InputArgument::REQUIRED, 'string containing url')
                ->setDescription('Crawl starting at a given url')
                ->setHelp('the fromurl command takes an absolute url as starting point for the crawling
                example:
                php crawler.php url "http://www.bbc.co.uk"')
                ->addOption(
                        'tweaks', null, InputOption::VALUE_REQUIRED, 'path to xml file with tweaks?', 1)
                ->addOption(
                        'filters', null, InputOption::VALUE_REQUIRED, 'path to xml file with filters', 1)
                ->addOption(
                        'store', null, InputOption::VALUE_REQUIRED, 'store html of each page', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getArgument('fromurl');

         $servicecontainer = new ContainerBuilder();
         $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__.'/../config/'));
         $loader->load("dic.xml");

        $db = $servicecontainer->get('newdb');
        $db->initCrawler($domain);

        if (gettype($input->getOption('tweaks')) == 'string') {
            $tweaksloc = $input->getOption('tweaks');
            //update parameter in dic
            $servicecontainer->setParameter('tweaks.constructorinput', $tweaksloc);
        }

        $tweaks = $servicecontainer->get('tweaks');
        $tweaks->init();

        if (gettype($input->getOption('filters')) == 'string') {
            $filter = $input->getOption('filters');
            //update parameter in dic
            $servicecontainer->setParameter('filters.constructorinput', $filter);
        }
        $filters = $servicecontainer->get('filters');
        $filters->init();

        if ($input->getOption('store')) {
            $store = true;
        } else {
            $store = false;
        }
        $crwlrsettings = new Classes\CrawlerSettings($domain, "domain", $db, $tweaks, $filters,$store, true, true);

        //*** setup crawler ***
        $output->writeln("starting Crawler \n");
        $s = microtime(true);
        $scraper = new Classes\Crawler($crwlrsettings);
        unset($scraper);
        $e = microtime(true);
        $totaltime = round($e - $s, 2) . " Seconds";
        $output->writeln("finished crawling in" . $totaltime);
    }

    }
