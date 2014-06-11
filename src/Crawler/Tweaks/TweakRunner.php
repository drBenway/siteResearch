<?php

/**
 * factory for tweaks
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Tweaks;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Crawler\Classes as Classes;

/**
 * factory for filters
 */
class TweakRunner implements Classes\RunnerInterface
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
    public function __construct($configxml = "__DIR__./tweaks.xml")
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
            exit('Failed to open config file for Tweaks:' . $this->configfile);
        }

        $servicecontainer = new ContainerBuilder();
        $loader = new XmlFileLoader($servicecontainer, new FileLocator(__DIR__ . "/../config/"));
        $loader->load("dic.xml");

        $xml = simplexml_load_file($this->configfile);
        foreach ($xml->tweak as $tweak) {
            $classname = (string) $tweak;
            $obj = $servicecontainer->get("$classname");
            array_push($this->list, $obj);
        }
    }

    /**
     * returns all the filters found in the config xml
     * @return type
     */
    public function getTweakList()
    {
        return $this->list;
    }

    /**
     * runs provided set of urls trough a list of tweaks
     * @param  array $urls
     * @return array
     */
    public function run(array $urls)
    {
        foreach ($this->list as $tweak) {
            $urls = $tweak->tweak($urls);
        }

        return $urls;
    }

}
