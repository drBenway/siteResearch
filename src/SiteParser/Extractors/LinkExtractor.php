<?php

namespace SiteParser\Extractors;

class LinkExtractor implements SiteParser\Extractor
{
    private $domain = "http://github.com/drBenway/siteResearch/";

    public function _constructor($url)
    {
        //=> call db from html from url
        $crwlr = new DomCrawler\Crawler($html, $url);
        $nodes = $crwlr->filter('a');

        foreach ($nodes->links() as $link) {
            array_push($this->urls, $link->getUri());
        }

        foreach ($this->urls as $url) {
            echo $page . $domain . "link" . " " . $url;
        }
    }

    public function extract()
    {

    }

}
