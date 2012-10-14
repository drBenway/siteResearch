<?php

/**
 * tweak strip index from url
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters

 */

namespace siteResearch\Crawler\Tweaks;

/**
 * class to strip the index (expl index.php,index.asp) from urls
 *
 * if you don't strip the index, you risk having 2 links to the same page in
 * your crawlerdatabase expl: www.github.com and www.github.com/index.php are the same page
 */
class stripIndex implements TweakInterface
{
    /**
     * strip every version of index page
     * @param string $url
     * @return string
     * @throws Exception
     */
    private function strip($url)
    {
        $Indexes = array('index.php', 'index.aspx', 'index.asp', 'index.html', 'index.htm', 'index.jsp');
        if (is_string($url)) {
            foreach ($Indexes as $index) {
                $url = str_replace($index, "", $url);
            }
        } else {
            throw new Exception('function stripIndex recieved a wrong parameter. String needed');
        }

        return $url;
    }

    /**
     * strips index from given urls
     * @param array $urls
     * @return array
     */
    public function tweak($urls)
    {
        $returnarray = array();
        foreach ($urls as $url) {
            array_push($returnarray, $this->strip($url));
        }
        return $returnarray;
    }

}
