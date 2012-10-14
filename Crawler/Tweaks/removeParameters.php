<?php

/**
 * tweak strip all parameters from url
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters

 */

namespace siteResearch\Crawler\Tweaks;

class removeParameters implements TweakInterface
{
    private function stripParameters($url)
    {
        if (is_string($url)) {
            $pos = strpos($url, '?');
            $hash = strpos($url, "#");
            if ($hash === false) {
                if ($pos !== false) {
                    return substr($url, 0, $pos);
                } else {
                    return $url;
                }
            } else {
                return substr($url, 0, $pos) . substr($url, $hash);
            }
        } else {

            throw new Exception("function removeParameters recieved a wrong parameter. String needed");
        }
    }

    public function tweak($urls)
    {
        $returnarray = array();
        foreach ($urls as $url) {
            array_push($returnarray, $this->stripParameters($url));
        }
        return $returnarray;
    }

}
