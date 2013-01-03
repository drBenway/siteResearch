<?php

/**
 * factory for tweaks
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Tweaks;

/**
 * factory for filters
 */
class TweakRunner
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
        $this->configfile = $configxml;
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

        $xml = simplexml_load_file($this->configfile);
        foreach ($xml->tweak as $tweak) {
            $classname = (string) $tweak;
            $classname = "Crawler\\Tweaks\\".$classname;
            $obj = new $classname();
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
