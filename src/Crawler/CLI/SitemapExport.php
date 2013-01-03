<?php

namespace Crawler\CLI;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Crawler\Export as Export;

class SitemapExport extends Console\Command\Command
{
    protected function configure()
    {
        $this
                ->setName('sitemapexport')
                ->addArgument('sitemapexport', InputArgument::REQUIRED, 'export content')
                ->setDescription('export sitemap file')
                ->setHelp('export sitemap file
                            example: php crawler.php sitemapexport "test.xml";
                            ');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filepath = $input->getArgument('sitemapexport');
        if (gettype($filepath) == 'string') {
            $exporter = new Export\SitemapExport($filepath);
            $exporter->export();
            $output->writeln("created sitemap at " . $filepath);
        } else {
            $output->writeln("failed to output sitemap");
        }
    }
    }
