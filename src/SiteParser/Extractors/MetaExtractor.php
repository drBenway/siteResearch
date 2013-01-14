<?php

/**
 * contains class pagecrawler
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace SiteParser\Extractors;

use Symfony\Component\DomCrawler as DomCrawler;

/**
 * Analyse a single page
 *
 * @author Yves Peeters
 */
class MetaExtractor extends Extractor{
private $domain = "http://github.com/drBenway/siteResearch/";

function _constructor($url)
{
        //=> call db from html from url
        $crwlr = new DomCrawler\Crawler($html);
        $nodes = $crwlr->filter('meta');
        //print_r($nodes->links());

        foreach ($nodes as $key=>$value) {
            echo "$url"." ".$domain.$key." ". $value;
        }
}


}

?>
