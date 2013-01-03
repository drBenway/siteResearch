<?php

/**
 * tweak: return url in lowercase
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Tweaks;

/**
 * tweak to alter urls to lowercase
 */
class toLowercase implements TweakInterface
{
    /**
     * turns a given set of urls into a lowercase version
     * @param  array $urls
     * @return array
     */
    public function tweak($urls)
    {
        $returnarray = array();
        foreach ($urls as $url) {
            array_push($returnarray, strtolower($url));
        }

        return $returnarray;
    }

}
