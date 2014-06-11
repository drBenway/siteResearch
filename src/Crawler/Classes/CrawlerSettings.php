<?php

/**
 * config class for crawler
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Classes;

/**
 * holds the configuration data for the crawler
 *
 * url, crawletype, database link and filters are mandatory
 */
Class CrawlerSettings {

    /**
     * start url
     * @var string
     */
    private $url;

    /**
     * crawle type, this is verified by the crawler agains an array of crawltypes
     * @var string
     */
    private $type;

    /**
     * do we want to strip index.html, index.php from the url's that we store
     * @var boolean
     */
    private $stripIndex;

    /**
     * do we want to remove parameters from urls that we store
     * @var boolean
     */
    private $stripParameters;

    /**
     * link to the databaseclass stat stores our urls
     * @var objectr database object
     */
    private $db;

    /**
     * link to the filters object
     * @var object filters
     */
    private $filters;

    /**
     * link to the tweak object
     * @var tweaks
     */
    private $tweaks;

    /**
     * object to fetch pages
     * @var object(curl)
     */
    private $curl;

    /**
     * should the crawler store the obtained html yes/no
     * @var type
     */
    private $store;

    /**
     * constructor for crawlersettings
     * @param string  $url
     * @param string  $type
     * @param object  $db
     * @param object  $tweaks
     * @param object  $filters
     * @param boolean $store
     * @param boolean $stripIndex
     * @param boolean $stripParameters
     */
    public function __construct($url, $type, \Crawler\DB\DatabaseInterface $db, $tweaks,   $filters, $store = false, $stripIndex = true, $stripParameters = true)
    {
        $this->url = $url;
        $this->type = $type;
        $this->db = $db;
        $this->filters = $filters;
        $this->stripIndex = $stripIndex;
        $this->stripParameters = $stripParameters;
        $this->tweaks = $tweaks;
        $this->store = $store;
    }

    /**
     * sets the url to parse
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * set the parsing type
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Do you want to strip the index yes/no
     * @param boolean $stripIndex
     */
    public function setStripIndex($stripIndex)
    {
        $this->stripIndex = $stripIndex;
    }

    /**
     * do you want to strip parameters from the url yes/no
     * @param boolean $stripParameters
     */
    public function setStripParameters($stripParameters)
    {
        $this->stripParameters = $stripParameters;
    }

    /**
     * use this database to store the results
     * @param DatabaseInterface $db
     */
    public function setDb(\Crawler\DB\DatabaseInterface $db)
    {
        $this->db = $db;
    }

    /**
     * selects a filterset
     * @param FilterInterface $filters
     */
    public function setFilters(\Crawler\Filters\FilterInterface $filters)
    {
        $this->filters = $filters;
    }

    /**
     * select a tweaks set
     * @param TweakInterface $tweaks
     */
    public function setTweaks(\Crawler\Tweaks\TweakInterface $tweaks)
    {
        $this->tweaks = $tweaks;
    }

    /**
     * curl object to be used
     * @param type $curl
     */
    public function setCurl($curl)
    {
        $this->curl = $curl;
    }

    /**
     * get the starting url
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * get the crawler type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * do we strip index files from urls?
     * @return boolean
     */
    public function getStripIndex()
    {
        return $this->stripIndex;
    }

    /**
     * do we strip parameters from urls?
     * @return boolean
     */
    public function getStripParameters()
    {
        return $this->stripParameters;
    }

    /**
     * return database object
     * @return object
     */
    public function getDb()
    {
        return $this->db;
    }

    /**
     * return filter object
     * @return object
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * returns Tweak object
     * @return object
     */
    public function getTweaks()
    {
        return $this->tweaks;
    }

    /**
     * returns curl object
     * @return object
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * set the store html property
     * @param boolean $store
     */
    public function setStore($store)
    {
        $this->store($store);
    }

    /**
     * get the store html property
     * @return boolean
     */
    public function getStore()
    {
        return $this->store;
    }

}
