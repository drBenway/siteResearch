<?php

/**
 * tweak strip hash from url
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters

 */

namespace Crawler\Tweaks;

class stripHash implements TweakInterface
{
    private function removeHash($url)
    {
        if (is_string($url)) {

            $hash = strpos($url, "#");

            if ($hash !== false) {
                return substr($url, 0, $hash);
            } else {
                return $url;
            }
        } else {

            throw new Exception("function stripHash recieved a wrong parameter. String needed");
        }
    }

    public function tweak($urls)
    {
        $returnarray = array();
        foreach ($urls as $url) {
            array_push($returnarray, $this->removeHash($url));
        }

        return $returnarray;
    }

}
