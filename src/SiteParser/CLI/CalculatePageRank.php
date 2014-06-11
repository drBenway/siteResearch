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
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
 * crawler command to export crawler table to a csv file
 */
class CalculatePageRank extends Console\Command\Command
{
    /**
     * setup calculator
     */
    protected function configure()
    {

    }

    /*
     * execute pagerank calculator
     */

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }

    }
