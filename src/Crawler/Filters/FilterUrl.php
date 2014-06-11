<?php

/**
 * Class to filter urls
 * @package siteResearch
 * @subpackage Crawler
 * @author Yves Peeters
 */

namespace Crawler\Filters;

/**
 * filters urls based on a given set of strings
 *
 * All urls that don't have these strings will be filtered out
 *
 */
class FilterUrl implements FilterInterface
{
    /**
     * holds the domain to crawl
     * @var type
     */
    private $tofilter = array();

    /**
     * construct FilterExternalUrls object
     * @param string $config
     */
    public function __construct($configfile)
    {
        $this->loadConfig(__DIR__."/".$configfile);
    }

    public function loadConfig($config)
    {
        $xml = simplexml_load_file($config);
        if (!isset($xml)) {
            exit("could not load xml file for FilterUrls: " . $config);
        }
        foreach ($xml->url as $url) {
            array_push($this->tofilter, (string) $url);
        }
    }

    public function filter($url)
    {
        $return = false;

        foreach ($this->tofilter as $filter) {
            $pos = strpos($url, $filter);

            if ($pos !== false) {
                $return = true;
            }
        }

        return $return;
    }

}
