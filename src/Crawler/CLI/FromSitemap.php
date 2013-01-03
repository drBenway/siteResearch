<?php

namespace Crawler\CLI;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\INputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Crawler\DB as DB,
    Crawler\Tweaks as Tweaks,
    Crawler\Filters as Filters,
    Crawler\Classes as Classes,
    Crawler\Import as Import;

class FromSitemap extends Console\Command\Command {

    protected function configure() {
        $this
                ->setName('fromsitemap')
                ->addArgument('fromsitemap', InputArgument::REQUIRED, 'string containing path to sitemap')
                ->setDescription('The sitemap we want to crawl')
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

    protected function execute(InputInterface $input, OutputInterface $output) {

        
        
        $filepath = $input->getArgument('fromsitemap');
        
        /**
         * import sitemap
         */
         
        $importer = new Import\SitemapImport($filepath);
        $importer->import();
        
        /**
         * start crawler
         */
        
        $db = new DB\CrawlerDB;

        if (gettype($input->getOption('tweaks')) == 'string') {
            
            $tweaksloc = $input->getOption('tweaks');
            $tweaks = new Tweaks\TweakRunner($tweaksloc);
            $tweaks->init();
        } else {
            $tweaks = new Tweaks\TweakRunner('Crawler/Tweaks/tweaks.xml');
            
            $tweaks->init();

        }
        
        
        
        
        if (gettype($input->getOption('filters')) == 'string') {
            
            $filters = $input->getOption('filters');
        } else {
            $filters = new Filters\FilterRunner('Crawler/Filters/filters.xml');
            $filters->init();
        }
        
        if ($input->getOption('store'))
        {
            $store = true;
        }
        else{
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

 