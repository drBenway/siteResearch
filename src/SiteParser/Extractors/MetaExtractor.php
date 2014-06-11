<?php

/**
 * contains class pagecrawler
 *
 * @package siteResearch
 * @subpackage SiteParser
 * @author Yves Peeters
 */

namespace SiteParser\Extractors;

use Symfony\Component\DomCrawler as DomCrawler;

/**
 * Analyse a single page
 *
 * @author Yves Peeters
 * @todo store in db
 */
class MetaExtractor implements Extractor
{
    private $domain = "http://github.com/drBenway/siteResearch/";

    public function _constructor($url)
    {
        //=> call db from html from url
        $crwlr = new DomCrawler\Crawler($html);
        $nodes = $crwlr->filter('meta');
        //print_r($nodes->links());

        foreach ($nodes as $key => $value) {
            echo "meta" . " " . $domain . "MetaExtractotr" . " " . $domain . $key;
            echo "$url" . " " . $domain . $key . " " . $value;
        }
    }

    public function extract()
    {

    }

}
