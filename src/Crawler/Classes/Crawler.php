<?php

/**
 * main file for the crawler
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 * @todo add option to import all known links from google
 * @todo add option to import all links from sitemap.xml file
 */

namespace Crawler\Classes;

/**
 * Crawls one or more websites for url
 *
 * A pagequeue is created in the database and the url is added.
 * Crawler then parses the first page in the queue (the url that was recieved as
 * parameter) and adds all found links to the queue.
 * After that the crawler looks for the next unparsed page and adds the result
 * to the back of the queue.
 * This repeats until the entire queue has been checked.
 *
 * <img src="http://www.westworld.be/siteResearch/Design/images/crawler.gif" /><br />
 * The script can take some time to finish. It is advised to give php more
 * memory and time with
 * <code>
 * ini_set("memory_limit","xxxM");
 * set_time_limit(xxx);// time in seconds
 * </code>
 * Exmpl.
 * <code>
 * $url = 'https://github.com/drBenway/siteResearch/';
 * $scraper = new Crawler($url);
 * </code>
 *
 */
class Crawler
{
    /**
     * object containing all the settings for the crawler
     * @var type
     */
    private $settings;

    /**
     * database connection
     * @var type
     */
    private $db;

    /**
     * Holds the current page
     * @var String $current_page
     */
    private $current_url;

    /**
     * constructor
     * @param CrawlerSettings $settings
     */
    public function __construct(CrawlerSettings $settings)
    {
        $this->settings = $settings;
        $this->db = $this->settings->getDb();
        $this->tweakFactory = $this->settings->getTweaks();
        $this->filterFactory = $this->settings->getFilters();
        $this->startCrawler();
        //@todo: if savehtml is true $this->startCrawlerWithSave();
    }

    /**
     * gets the currtent page
     * @return String
     */
    private function getCurrentUrlObj()
    {
        return $this->current_url;
    }

    /**
     * sets the current page
     * @param String $current_page
     */
    private function setCurrentUrlObj($current_page)
    {
        $this->current_url = $current_page;
    }

    /**
     * Start crawling
     * {@filesource}
     * @todo try to move new objects outside of loop. di container is for later
     * @todo think $this->db->getUrlsToDo() add new function that querys and halts if one url is found
     */
    public function startCrawler()
    {

        while (count($this->db->getUrlsToDo()) > 0) {// Check db for urls that have not been parsed
            //$starttime = microtime(true);

            $this->setCurrentUrlObj($this->db->getUrlToWorkOn()); // get a page to parse
            $this->crwlr = new CurlHelper();
            $this->crwlr->init();
            $request = $this->crwlr->validHtmlPage($this->getCurrentUrlObj());

            if ($request === true) {// check if the page exists
                $this->getCurrentUrlObj()->setFound(true);
                $this->getCurrentUrlObj()->setHeader(200);
                $this->db->saveUrl($this->getCurrentUrlObj());
                $s = microtime(true);

                $this->crwlr->init();
                $thishtml = $this->crwlr->getContent($this->getCurrentUrlObj());
                        $e = microtime(true);
        $totaltime = round($e - $s, 2) . " Seconds \n";
        echo $totaltime;
                $page = new PageCrawler($thishtml, $this->getCurrentUrlObj()); // get page content
                $this->saveHTML($thishtml);
                $this->addUrls($page->getPageUrls(), $this->getCurrentUrlObj());

            } else {
                $httpheader=$this->crwlr->httpHeaders($this->getCurrentUrlObj());// get http header
                $this->getCurrentUrlObj()->setHeader($httpheader);
                $this->db->saveUrl($this->getCurrentUrlObj());
                if ($this->crwlr->pageMoved($this->getCurrentUrlObj())) {// test if current url has redirect header if yes, add the redirect location to the queue
                    $newurl[0] = $this->crwlr->getRedirectLocation($this->getCurrentUrlObj());
                    $this->addUrls($newurl, $this->getCurrentUrlObj()); // add found urls to queue
                } else {
                    $this->getCurrentUrlObj()->setFound(false);
                    $this->db->setUrlAsBad($this->getCurrentUrlObj()); // set found in db to false
                }
            }
            unset($this->crwlr);

echo ".";
        }
    }

    /**
     * saves html from page to database
     * @param type $html
     */
    private function saveHtml($html)
    {

        $md5 = md5($html);
        $this->db->saveHTML($this->getCurrentUrlObj(), $html, $md5);
    }

    /**
     * adds urls to queue
     * {@filesource}
     * @param Array  $urls
     * @param string $page
     */
    private function addUrls($urls)
    {
        if ((array) $urls == $urls) {
            //$all = $urls;

            $delta = $this->runTweaks($urls);
            $delta = $this->runFilters($delta);
            $delta = array_unique($delta);
            $alpha = $delta;
            $delta = array_diff($delta, $this->db->getUrlsAsArray()); // get list of urls that have not been checked

            $this->db->addUrls($delta, $this->getCurrentUrlObj());
            $alpha = \array_unique($alpha);

            $this->db->storeLinking($alpha,$this->getCurrentUrlObj()); // keep links in seperate table for later use
        }
    }

    /**
     * runs a list of provided tweaks over a given list of urls
     * @param  type  $urls
     * @return array
     */
    private function runTweaks($urls)
    {
        return $this->tweakFactory->run($urls);
    }

    /**
     * runs a set of filters over a given list of urls
     * @param array $urls
     */
    private function runFilters($urls)
    {
        return $this->filterFactory->run($urls);
    }

}
