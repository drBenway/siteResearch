<?php

/**
 * interface for the crawler database
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\DB;

interface DatabaseInterface
{
    /**
     * adds url to database
     * @param array  $urls
     * @param string $origin
     */
    public function addUrls($urls, $origin);

    /**
     * gets an uparsed url
     */
    public function getUrlToWorkOn();

    /**
     * sets a given url as parsed
     * @param string $url
     */
    public function setUrlAsDone($url);

    /**
     * get a list of all urls that are unparsed
     */
    public function getUrlsToDo();

    /**
     * obtain a list of all parsed urls
     */
    public function getUrlsDone();

    /**
     * set a url to bad (expl error 404)
     * @param string $url
     */
    public function setUrlAsBad($url);

    /**
     * set url as valid (http header 200)
     * @param string $url
     */
    public function setUrlAsGood($url);

    /**
     * store the responsheader for a given url
     * @param string $header
     * @param string $url
     */
    public function setResponsHeader($header, $url);
}
