<?php

/**
 * tweak remove all none html urls
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters

 */

namespace siteResearch\Crawler\Tweaks;

class keepOnlyPages implements TweakInterface
{
    public function tweak($urls)
    {
        /**
        $returnarray = array();
        $curl = new PHP_Curl_Crawler();
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
