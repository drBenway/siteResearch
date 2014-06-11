<?php

/**
 * Import from sitemap command
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\CLI;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\INputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Crawler\DB as DB,
    Crawler\Tweaks as Tweaks,
    Crawler\Filters as Filters,
    Crawler\Classes as Classes,
    Crawler\Import as Import;

/**
 * start crawling based on a sites sitemap file
 */
class FromSitemap extends Console\Command\Command
{
    protected function configure()
    {
        $this
                ->setName('fromsitemap')
                ->addArgument('fromsitemap', InputArgument::REQUIRED, 'string containing path to sitemap')
                ->setDescription('start crawling based on a sitemap file')
                ->setHelp('the sitemap command takes an absolute url as starting point for the crawling
                example:
                php crawler.php fromsitemap "http://www.bbc.co.uk/sitemap.xml"')
                ->addOption(
                        'tweaks', null, InputOption::VALUE_REQUIRED, 'path to xml file with tweaks?', 1)
                ->addOption(
                        'filters', null, InputOption::VALUE_REQUIRED, 'path to xml file with filters', 1)
                ->addOption(
                        'store', null, InputOption::VALUE_REQUIRED, 'store html of each page', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $servicecontainer = new ContainerBuilder();
         $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__.'/../config/'));
         $loader->load("dic.xml");

        $filepath = $input->getArgument('fromsitemap');

        /**
         * import sitemap
         */

        $importer = new Import\SitemapImport($filepath);
        $importer->import();

        /**
         * start crawler
         */

        $db = $servicecontainer->get('db');

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
        $crwlrsettings = new Classes\CrawlerSettings($filepath, "domain", $db, $tweaks, $filters,$store, true, true);

        //*** setup crawler ***
        $output->writeln("starting Crawler \n");
        $s = microtime(true);
        $scraper = new Classes\Crawler($crwlrsettings);
        unset($scraper);
        $e = microtime(true);
        $totaltime = round($e - $s, 2) . " Seconds";
        $output->writeln("finished crawling in" . $totaltime ."\n");
    }

    }
