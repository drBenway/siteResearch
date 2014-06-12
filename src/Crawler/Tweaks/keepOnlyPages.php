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
use Crawler\Entities as Entity;

/**
 * class to ping urls
 *
 * pings urls and checks the content_type
 * @todo implement this in the crawler, not in the tweaks since it's more a filter
 */
class keepOnlyPages implements TweakInterface
{

    /**
     * returns only urls with a content type html
     * @param  type  $urls
     * @return array
     */
    public function tweak($urls)
    {
        
        $returnarray = array();
        //$curl = new Classes\CurlHelper();
        //$curl->init();

        foreach ($urls as $url) {
            //$temp = new Entity\Links();
            //$temp->setUrl($url);
            //$info = $curl->getInfo($temp);
            //$mystring = $info["content_type"];
            //$found = stripos($mystring, "html");
            $temp = parse_url($url);
            
            if (isset($temp['path'])) {
                /*
                 * $findaspx = strpos($temp['path'], '.aspx');
                 * $findjsp = strpos($temp['path'], '.jsp');
                 * $findphp = strpos($temp['path'], '.php');
                 */
                $allowedvalues = array(".php", ".asp", ".aspx", ".jsp", ".htm", ".html");
                $lastchar = substr($temp['path'], -1);
                $extension = strtolower($temp['path']['extension']);
                
                if (in_array($extension, $allowedvalues) or $lastchar = "/") {
                    array_push($returnarray, $url);
                }
            } else {
                array_push($returnarray, $url);
            }
            //if ($found > 0) {
            //array_push($returnarray, $temp->getUrl());
            //}
        }
        //$curl->close();
        //unset($temp);
        return $returnarray;
    }

}
