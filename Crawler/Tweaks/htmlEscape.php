<?php

/**
 * tweak html escape
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace siteResearch\Crawler\Tweaks;



/**
 * escapes special characters in urls
 *
 */
class htmlEscape implements TweakInterface
{
    /**
     * loops through a given set of urls and returns an escaped version
     * @param array $urls
     * @return array
     */
    public function tweak($urls)
    {
        $returnarray = array();
        foreach ($urls as $url) {
            if (is_string($url)) {
                array_push($returnarray, htmlentities($url, ENT_QUOTES, "UTF-8"));
            }
        }
        return $returnarray;
    }

}
