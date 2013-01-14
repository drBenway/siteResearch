<?php

/**
 * contains class pagecrawler
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Classes;

use Symfony\Component\DomCrawler as DomCrawler;

/**
 * Analyse a single page
 *
 * @author Yves Peeters
 */
class PageCrawler
{
    /**
     * String with the content of the current page
     * @var String
     */
    private $pagehtml;

    /**
     * contains the url of the current page
     * @var String
     */
    private $pageurl;

    /**
     * Array with urls of the page
     * @var Array
     */
    private $urls = array();

    /**
     * constructor
     * @param string $html
     * @param string $page
     */
    public function __construct($html, $page)
    {
        $this->pagehtml = $html;
        $this->pageurl = $page;
    }

    /**
     * parsepage searches for urls and puts them in $urls
     * {@source}
     */
    private function parsePage()
    {
        $crwlr = new DomCrawler\Crawler($this->pagehtml, $this->pageurl);
        $nodes = $crwlr->filter('a');
        //print_r($nodes->links());

        foreach ($nodes->links() as $link) {
            array_push($this->urls, $link->getUri());
        }
        unset($crwlr);
        $this->removeMailto();
        $this->removeJavascript();
        return $this->urls;
    }
    
    
    private function removeMailto()
    {
        $return = array();
        foreach ($this->urls as $url){
            if(strpos($url,'mailto:') === false)
            {
                array_push($return, $url);
            }
        }
        $this->urls= $return;  
    }
    
    private function removeJavascript ()
    {
         $return = array();
        foreach ($this->urls as $url){
            if(strpos($url,'javascript:') === false)
            {
                array_push($return, $url);
            }
        }
        $this->urls= $return;        
    }

    /**
     * returns a list with found urls
     * {@source}
     * @return array
     */
    public function getPageUrls()
    {
        $this->parsePage();

        return $this->urls;
    }

    public function getPageHTML()
    {
        return $this->pagehtml;
    }

}
