<?php
/**
 * interface for tweaks
 *
  * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */
namespace siteResearch\Crawler\Tweaks;
/**
 * Tweaks are scripts that need to run while the crawler is busy crawling pages.
 * These scripts are executed, after the crawler has finished crawling a page.
 *
 * All the found links are passed to the tweaks, before applying filters.
 *
 * Multiple tweaks can be executed per page.
 *
 * Tweak scripts should be stored in the tweak folder.
 * It is adviced to add a comment at the top of your tweak script (phpdocumentor
 * style) to indicate what the script is.
 */
interface TweakInterface
{
    /**
     * required method
     * @param string url
     */
    public function tweak($url);
}
