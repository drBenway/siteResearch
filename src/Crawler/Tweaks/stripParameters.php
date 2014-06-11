<?php

/**
 * tweak strip all parameters from url
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters

 */

namespace Crawler\Tweaks;

/**
 * remove parameters from a url
 *
 * removes everything from ? till the end of the url or the hash # sign
 */
class stripParameters implements TweakInterface
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
        if (is_array($urls)) {
            $returnarray = array();
            foreach ($urls as $url) {
                array_push($returnarray, $this->stripParameters($url));
            }

            return $returnarray;
        } else {
            /* @todo */
            throw new \InvalidArgumentException("stripParameters expects arguments to");
        }
    }

}
