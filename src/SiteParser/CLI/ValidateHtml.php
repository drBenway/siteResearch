<?php
/**
 * validate html page
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
 * crawler command to export crawler table to a csv file
 */
class ValidateHtml extends Console\Command\Command
{
    /**
     * setup validatehtml
     */
    protected function configure()
    {
        $this
                ->setName('validateHtml')
                ->addArgument('csvexport', InputArgument::REQUIRED, 'export content')
                ->setDescription('validate the stored html with W3C validator')
                ->setHelp('
                    export the crawler database to a csv file (can be used for example in Excel)\n
                    example: php crawler.php csvexport "test.csv"
                    ');

    }

    /**
     * add validatehtml command
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }

    }
