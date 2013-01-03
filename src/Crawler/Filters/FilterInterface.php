<?php

/**
 * interface for filters
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Filters;

/**
 * Filters are scripts that need to run while the crawler is busy crawling pages.
 * These scripts are executed, after the crawler has finished crawling a page.
 *
 * All the found links are passed to the filter, as is the current page.
 *
 * Multiple filters can be executed per page. Therefore it is important that a list
 * with urls is given as parameter to the filter and a list with urls is returned
 * when the filter has finished.
 *
 * Filter scripts should be stored in the filter folder.
 * It is adviced to add a comment at the top of your filter script (phpdocumentor
 * style) to indicate that the script is an filter and to explain what it does.
 */
interface FilterInterface
{
    /**
     * required method
     * @param string url
     */
    public function filter($url);
}
