<?php

/**
 * contains class pagecrawler
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace siteResearch\Crawler\Classes;


/**
 * Analyse a single page
 *
 * @author Yves Peeters
 */
class PageCrawler
{
    /**
     * String met de inhoud van een pagina in html
     * @var String
     */
    private $page;

    /**
     * Array met urls uit een pagina
     * @var Array
     */
    private $urls = array();

    /**
     * constructor
     * @param type $html
     */
    public function __construct($html)
    {
        $this->page = $html;
    }

    /**
     * parsepage searches for urls and puts them in $urls
     * {@source}
     */
    private function parsePage()
    {
        
        $html = new simple_html_dom();
        $html->load($this->page);
        $links = $html->find('a[href]');
        foreach ($links as $link) {
            array_push($this->urls, $link->href);
        }
        $html->clear();
        unset($html);
        unset($links);
    }

    /**
     * returns a list with found urls
     * {@source}
     * @return array
     */
    public function getPageUrls()
    {
        $this->parsePage();
        print_r($this->urls);
        return $this->urls;
    }

}
