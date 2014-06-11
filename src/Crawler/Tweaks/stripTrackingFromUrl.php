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
class stripTrackingFromUrl implements TweakInterface
{
    private function stripTracking($url)
    {
        if (is_string($url)) {
            $pos = strpos($url, '?');
            $hash = strpos($url, "#");
            $params = "";
            $output = array();
            if ($hash === false) {
                if ($pos !== false) {
                    $params = substr($url, $pos + 1);

                    $paramlist = explode("&", $params);

                    foreach ($paramlist as $item) {

                        if (substr($item, 0, 7) == "tabname") {

                            array_push($output, $item);
                        }
                    }
                    if (count($output) > 0) {
                        return substr($url, 0, $pos) . "?" . implode("&", $output);
                    } else {
                        return substr($url, 0, $pos);
                    }
                } else {
                    return $url;
                }
            }
        } else {

            throw new Exception("function stripTracking recieved a wrong parameter. String needed");
        }
    }

    public function tweak($urls)
    {

        if (is_array($urls)) {
            $returnarray = array();
            foreach ($urls as $url) {
                array_push($returnarray, $this->stripTracking($url));
            }

            return $returnarray;
        } else {
            /* @todo */
            throw new \InvalidArgumentException("stripTracking expects arguments to be array of urls");
        }
    }

}
