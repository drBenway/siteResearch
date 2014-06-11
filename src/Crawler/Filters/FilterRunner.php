<?php

/**
 * factory for filters
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Filters;

  use  Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
 Crawler\Classes\RunnerInterface as RunnerInterface;
/**
 * factory for filters
 */
class FilterRunner implements RunnerInterface
{
    /**
     * stores a list of all the filters to run
     * @var type
     */
    private $list = array();

    /**
     * stores the location of the xml config file
     * @var type
     */
    private $configfile;

    /**
     * constructor
     * @param string $configxml path to config xml
     */
    public function __construct($configxml)
    {
        $this->configfile = __DIR__."/".$configxml;
    }

    /**
     * parse the config xml
     *
     * parses the config xml and puts the content in $list
     */
    public function init()
    {
        if (!file_exists($this->configfile)) {
            exit('Failed to open config file for Filters:' . $this->configfile);
        }

        $servicecontainer = new ContainerBuilder();
         $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__."/../config/"));
         $loader->load("dic.xml");

        $xml = simplexml_load_file($this->configfile);
        foreach ($xml->filter as $filter) {
            $classname = (string) $filter;
            $obj = $servicecontainer->get("$classname");
            array_push($this->list, $obj);
        }
    }

    /**
     * returns all the filters found in the config xml
     * @return type
     */
    public function getFilterList()
    {
        return $this->list;
    }
    /**
     * runs list of urls throught a set of filters
     * @param  array $urls
     * @return array
     */
    public function run(array $urls)
    {

        $returnarray = array();
        foreach ($urls as $url) {
            $strip = false;
            foreach ($this->list as $filter) {

                $result = $filter->filter($url);

                if ($result === true) {
                    $strip = true;
                }
            }
            if ($strip === false) {
                array_push($returnarray, $url);
            }
        }

        return $returnarray;
    }

}
