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
    private $current_page;

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
    public function getCurrentPage()
    {
        return $this->current_page;
    }

    /**
     * sets the current page
     * @param String $current_page
     */
    public function setCurrentPage($current_page)
    {
        $this->current_page = $current_page;
    }

    /**
     * Start crawling
     * {@filesource}
     * @todo try to move new objects outside of loop. di container is for later
     */
    public function startCrawler()
    {
        while (count($this->db->getUrlsToDo()) > 0) {// Check db for urls that have not been parsed
            $starttime = microtime(true);
            $this->setCurrentPage($this->db->getUrlToWorkOn()); // get a page to parse
            $this->crwlr = new PHPCurlCrawler();
            $this->crwlr->init();
            $request = $this->crwlr->validHtmlPage($this->getCurrentPage());

            if ($request === true) {// check if the page exists
                $this->db->setResponsHeader(200, $this->getCurrentPage());
                $this->db->setUrlAsGood($this->getCurrentPage()); // set found in db to true
                //$crwlr->close();
                $this->crwlr->init();
                $thishtml = $this->crwlr->getContent($this->current_page);
                $page = new PageCrawler($thishtml, $this->current_page); // get page content
                $this->saveHTML($thishtml);
               // $crwlr->close();
                //unset($crwlr);
                $this->addUrls($page->getPageUrls(), $this->getCurrentPage()); // add found urls to queue
            } else {
                $this->db->setResponsHeader($this->crwlr->httpHeaders($this->getCurrentPage()), $this->getCurrentPage());
                if ($this->crwlr->pageMoved($this->getCurrentPage())) {// test if current url has redirect header if yes, add the redirect location to the queue
                    $newurl[0] = $this->crwlr->getRedirectLocation($this->getCurrentPage());
                    $this->addUrls($newurl, $this->getCurrentPage()); // add found urls to queue
                } else {
                    $this->db->setUrlAsBad($this->getCurrentPage()); // set found in db to false
                }
            }
            unset ($this->crwlr);
            echo ".";
            $endtime = microtime(true);
            $elapsed = $endtime - $starttime;
            //@todo do something with the elapsed time or remove it
        }
    }

    /**
     * saves html from page to database
     * @param type $html
     */

    public function saveHtml($html)
    {
        $this->db->saveHTML($this->getCurrentPage(),$html);

    }
    /**
     * adds urls to queue
     * {@filesource}
     * @param Array  $urls
     * @param string $page
     */
    public function addUrls($urls, $page)
    {
        if ((array) $urls == $urls) {
            //$urls = $this->makeUrlsAbsolute($urls, $this->getCurrent_page());
            $urls = $this->runTweaks($urls);
            $urls = $this->runFilters($urls);
            $urls = array_diff($urls, $this->db->getUrlsDone(), $this->db->getUrlsToDo()); // get list of urls that have not been checked
            $urls = array_unique($urls); // keep only the unique urls
            $this->db->addUrls($urls, $page);
        }
    }

    /**
     * returns one url to start scraping
     * {@filesource}
     * @return String
     */
    /*
    private function getUrlToWorkOn()
    {
        $todo = $this->db->getUrlToWorkOn();

        return $todo;
    }*/

    /**
     * makes sure that all urls in array are absolute path and not relative
     * @param  array  $urls
     * @param  string $currentpage
     * @return array

      private function makeUrlsAbsolute($urls, $currentpage)
      {
      $returnurls = array();
      foreach ($urls as $url) {
      array_push($returnurls, $this->relative_to_abolute_url($currentpage, $url));
      }

      return $returnurls;
      } */
    /**
     * returns the absolute path for a given url
     * @param  string $base
     * @param  string $href
     * @return string

      private function relative_to_abolute_url($base, $href)
      {
      // href="" ==> current url.
      if (!$href) {
      return $base;
      }

      // href="http://..." ==> href isn't relative
      $rel_parsed = parse_url($href);
      if (array_key_exists('scheme', $rel_parsed)) {
      return $href;
      }

      // add an extra character so that, if it ends in a /, we don't lose the last piece.
      $base_parsed = parse_url("$base ");
      // if it's just server.com and no path, then put a / there.
      if (!array_key_exists('path', $base_parsed)) {
      $base_parsed = parse_url("$base/ ");
      }

      // href="/ ==> throw away current path.
      if ($href{0} === "/") {
      $path = $href;
      } else {
      $path = dirname($base_parsed['path']) . "/$href";
      }

      // bla/./bloo ==> bla/bloo
      $path = preg_replace('~/\./~', '/', $path);

      // resolve /../
      // loop through all the parts, popping whenever there's a .., pushing otherwise.
      $parts = array();
      foreach (
      explode('/', preg_replace('~/+~', '/', $path)) as $part
      )
      if ($part === "..") {
      array_pop($parts);
      } elseif ($part != "") {
      $parts[] = $part;
      }

      return (
      (array_key_exists('scheme', $base_parsed)) ?
      $base_parsed['scheme'] . '://' . $base_parsed['host'] : ""
      ) . "/" . implode("/", $parts);
      }
     *
     *
     */

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
