<?php

/*
 * Class to filter urls
 * @package siteResearch
 * @subpackage Crawler
 * @author Yves Peeters
 */

namespace siteResearch\Crawler\Filters;

/**
 * filters urls based on a given set of strings
 *
 * All urls that don't have these strings will be filtered out
 *
 */
class FilterExternalUrls implements FilterInterface
{
    /**
     * holds the domain to crawl
     * @var type
     */
    private $domain = array();

    /**
     * construct FilterExternalUrls object
     * @param string $config
     */
    public function __construct($config = "filters/FilterExternalUrls.xml")
    {
        $this->loadConfig($config);
    }

    /**
     * loads config file (FilterExternalUrls.xml by default)
     * @param type $config
     */
    public function loadConfig($config)
    {
        $xml = simplexml_load_file($config);
        if (!isset($xml)) {
            exit("could not load xml file for FilterExternalUrls: " . $config);
        }
        foreach ($xml->domain as $domain) {
            array_push($this->domain, (string) $domain);
        }
    }

    /**
     * tests a given url
     * true => url in domain
     * false => url not in domain
     * @todo provide option for subdomains and '*'
     * @param string $url
     * @return boolean
     */
    public function filter($url)
    {
        $return = true;
        foreach ($this->domain as $domain) {
            $pos = strpos($url, $domain);

            if ($pos !== false) {
                $return = false;
            }
        }
        return $return;
    }

}
