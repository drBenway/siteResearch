<?php

/**
 * interface for the result database
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\DB;

interface DatabaseResultInterface
{

    /**
     * count all the urls with httpheader > 399
     * @param array  $urls
     * @param string $origin
     */
    public function countBadUrls();

    /**
     * count all urls in the database
     */
    public function countUrls();

    /**
     * get all the urls
     * @param string $url
     */
    public function  getAll();

    /**
     * get a list of all bad urls
     */
    public function getBadUrls();

    /**
     * get a list of all bad urls and the page containing them
     */
    public function getBadUrlsByReferer();

    /**
     * get al list of all good ulrs

     */
    public function getGoodUrls();

    /**
     * get a list of all good urls that contain $like
     * @param string $like
     */
    public function getGoodUrlsLike(string $like);

    /**
     * get a list of all redirected urls
     */
    public function getRedirectedUrls();
}
