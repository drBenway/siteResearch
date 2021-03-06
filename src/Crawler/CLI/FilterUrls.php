<?php

/**
 * FilterUrls command
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
 * crawler command filter results
 */
class FilterUrls extends Console\Command\Command
{
    /**
     * setup filterurls
     */
    protected function configure()
    {
        $this
                ->setName('filterurls')
                ->setDescription('manipulate results by removing urls')
                ->addOption(
                        'append', 'a', InputOption::VALUE_REQUIRED, 'add a string that should be in the results', 1)
                ->addOption(
                        'write', 'w', InputOption::VALUE_REQUIRED, 'write a new filtersettings file', 1)
                ->addOption(
                        'read', 'r', InputOption::VALUE_NONE, 'if set outputs the content of the xml that contains the settings')
                ->setHelp('
                    filter urls manages a list of strings that you want to
                    be in the urls of the crawler. If for example you only
                    want pages from bbc, you have to make sure that "bbc.co.uk"
                    is in your filter list.
                    if for example you only want to have the weather subdomain,
                    you add "weather.bbc.co.uk" to the list.

                    The list of settings are kept in an xml file in the
                    filter directory. It is structured like this:
                    <?xml version="1.0"?>
                    <FilterExternalUrls>
                        <domain>bbc.co.uk</domain>
                    </FilterExternalUrls>

                    You can make changes in the xml file itself or use this
                    command via the CLI to manage your changes.


                    Appending:
                    ----------
                    php crawler.php filterurls -a "mystring"
                    will add the "mystring" to the list resulting in the xml
                    file below:
                    <?xml version="1.0"?>
                    <FilterExternalUrls>
                        <domain>bbc.co.uk</domain>
                        <domain>mystring</domain>
                    </FilterExternalUrls>

                    Writing
                    -------
                    php crawler.php filterurls -w "www.mydomain.com,www.myotherdomain.com"
                    will overwrite the existing settings file and return the
                    content below:
                    <?xml version="1.0"?>
                    <FilterExternalUrls>
                        <domain>www.mydomain.com</domain>
                        <domain>www.myotherdomain.com</domain>
                    </FilterExternalUrls>

                    Reading
                    -------
                    To read the content of the settings file, just use the
                    filterurls command with the -r option
                    php crawler.php filterurls -r should return the correct
                    content

                    ');
        $this->servicecontainer = new ContainerBuilder();
        $loader = new XmlFileLoader($this->servicecontainer, new FileLocator(__DIR__ . '/../config/'));
        $loader->load("dic.xml");
        $this->dom = $this->servicecontainer->get("dom");
    }

    /**
     * add filterurls command
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();

        if ($options['read'] == "1") {
            $this->outputFile($output);
        } else {
            foreach ($options as $key => $value) {
                if (is_string($value)) {
                    switch ($key) {
                        case 'append':
                            $this->appendConfig($value);
                            $output->writeln("content appended");
                            break;
                        case 'write':
                            $this->writeConfig($value);
                            $output->writeln("content written");
                            break;

                        default:
                            break;
                    }
                }
            }
        }
    }

    protected function outputFile($output)
    {
        $file = file_get_contents(__DIR__ . "/../Filters/FilterExternalUrls.xml");
        $output->write($file);
    }

    protected function writeConfig($options)
    {

        $strings = explode(",", $options);
        $this->dom->formatOutput = true;
        $root = $this->dom->createElement("FilterExternalUrls");
        $this->dom->appendChild($root);
        foreach ($strings as $string) {
            $domain = $this->dom->createElement("domain");
            $text = $this->dom->createTextNode($string);
            $domain->appendChild($text);
            $root->appendChild($domain);
        }
        try {
            $this->dom->save(__DIR__ . "/../Filters/FilterExternalUrls.xml");
        } catch (Exception $e) {
            echo "Could not write filter file";
        }
    }

    protected function appendConfig($options)
    {
        $strings = explode(",", $options);

        $this->dom->load(__DIR__ . "/../Filters/FilterExternalUrls.xml");
        $root = $this->dom->getElementsByTagName("FilterExternalUrls")->item(0);
        foreach ($strings as $string) {
            $domain = $this->dom->createElement("domain");
            $text = $this->dom->createTextNode($string);
            $domain->appendChild($text);
            $root->appendChild($domain);
        }
        try {
            $this->dom->save(__DIR__ . "/../Filters/FilterExternalUrls.xml");
        } catch (Exception $e) {
            echo "Could not write/append to filter file";
        }
    }

}
