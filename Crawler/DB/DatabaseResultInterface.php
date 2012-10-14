<?php

/**
 * interface for crawler database
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 * @todo add option to import all known links from google
 * @todo add option to import all links from sitemap.xml file
 */

namespace siteResearch\Crawler\DB;

interface DatabaseResultInterface
{
    /**
     * obtain a list of all urls that have been redirected
     */
    public function getRedirectedUrls();

    /**
     * obtain the full crawler table
     */
    public function getAll();

    /**
     * count the total of all urls found
     */
    public function countUrls();

    /**
     * count all urls that have a responseheader different from 200 (expl 302, 404)
     */
    public function countBadUrls();

    /**
     * obtain a list of all bad urls (not httpheader 200)
     */
    public function getBadUrls();

    /**
     * obtain a list of all bad urls with the refering url
     */
    public function getBadUrlsByReferer();

    /**
     * obtain a list of all urls with header 200
     */
    public function getGoodUrls();

    /**
     * get a list of all good urls (header 200) that contain a given string
     * @param string $like
     */
    public function getGoodUrlsLike($like);
}
