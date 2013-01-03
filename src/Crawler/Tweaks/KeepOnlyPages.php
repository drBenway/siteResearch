<?php

/**
 * tweak remove all none html urls
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters

 */

namespace Crawler\Tweaks;
use Crawler\Classes as Classes;

/**
 * class to ping urls
*
 * pings urls and checks the content_type
 * @todo implement this in the crawler, not in the tweaks since it's more a filter
 */

class KeepOnlyPages implements TweakInterface
{
    /**
     * returns only urls with a content type html
     * @param  type  $urls
     * @return array
     */
    public function tweak($urls)
    {
        /**
        $returnarray = array();
        $curl = new Classes\PHPCurlCrawler();
        $curl->init();

        foreach ($urls as $url) {
            $info = $curl->getInfo($url);
            $mystring = $info["content_type"];
            $found = stripos($mystring, "html");
            if ($found > 0) {
                array_push($returnarray, $url);
            }
        }
        $curl->close();

        return $returnarray;

         */

        return $urls;
    }

}
