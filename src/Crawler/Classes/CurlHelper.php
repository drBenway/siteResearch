<?php

/**
 *  Curl class
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Classes;

/**
 * Curl based class to crawl webpages
 */
Class CurlHelper {

    /**
     * default user agent Mozilla
     * @var type
     */
    private $default_useragent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";

    /**
     * hold curl
     * @var type
     */
    private $curl;

    /**
     * close the connection
     * @return void
     */
    public function close()
    {
        curl_close($this->curl);
    }

    /**
     * initialize curl
     * @return void
     */
    public function init()
    {
        $this->curl = curl_init();
    }

    /**
     * login
     * @param type $loginActionUrl
     * @param type $parameters
     */
    public function logIn($loginActionUrl, $parameters)
    {
        curl_setopt($this->curl, CURLOPT_URL, $loginActionUrl);
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $parameters);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_exec($this->curl);
    }

    /**
     * checks http headers to detect if a pages exists
     * @param  String  $url
     * @return boolean
     */
    public function pageExists(\Crawler\Entities\Links $url)
    {
        try {
            $status = $this->httpHeaders($url->getUrl());

            if ($status = "200") {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * check http header return number
     * @param  type $url
     * @return int
     */
    public function pageStatus(\Crawler\Entities\Links $url)
    {
        try {
            $status = $this->httpHeaders($url);
        } catch (Exception $e) {
            return "404";
        }

        return $status;
    }

    /**
     * check if a page has been moved
     * @param  type    $url
     * @return boolean
     */
    public function pageMoved(\Crawler\Entities\Links $url)
    {

        $statusarray = array('301', '302', '307');
        if (in_array($url->getHeader(), $statusarray)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * optain redirect url
     * @param  type   $url
     * @return string
     */
    public function getRedirectLocation()
    {
        // @todo remove get_headers => doesn't support try/catch
        $headers = curl_getinfo($this->curl);
        if (isset($headers['Location'])) {
            return $headers['Location'];
        } else {
            return "";
        }
    }

    /**
     * returns the http headers for a page
     *
     * requests a page,only the headers, to see if it's available
     * @param  String $url
     * @return Array
     * @todo test speed vs pageheaders method
     *
     */
    public function httpHeaders(\Crawler\Entities\Links $url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url->getUrl());
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->default_useragent);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->curl, CURLOPT_NOBODY, TRUE);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, 'cookie.txt');
        $status = array();
        // preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($this->curl) , $status);
        curl_exec($this->curl);
        $status = curl_getinfo($this->curl);
        if (isset($status["http_code"])) {
            return $status["http_code"];
        } else {
            return "404";
        }
    }

    public function validHtmlPage(\Crawler\Entities\Links $url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url->getUrl());
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->default_useragent);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->curl, CURLOPT_NOBODY, TRUE);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, 'cookie.txt');

        // preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($this->curl) , $status);
        curl_exec($this->curl);
        $status = curl_getinfo($this->curl);
        if (!isset($status["http_code"])) {
            return false;
        } else {
            if ($status["http_code"] != '200') {
                return false;
            }
        }
        if (!isset($status["content_type"])) {
            return false;
        } else {

            $found = stripos($status["content_type"], "html");
            if ($found > 0) {
                return true;
            }
        }
    }

    /**
     * gets the content of url
     * @param  String $url
     * @return String
     */
    public function getContent(\Crawler\Entities\Links $url)
    {
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->default_useragent);
        curl_setopt($this->curl, CURLOPT_URL, $url->getUrl());
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false); // @todo proper fix for verification http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
        curl_setopt($this->curl, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, 'cookie.txt');
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        $content = curl_exec($this->curl);

        return $content;
    }

    /**
     * sends post var to url
     * @param String $url
     * @param String $postvar
     */
    public function postToPage(\Crawler\Entities\Links $url, $postvar)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url->getUrl());
        curl_setopt($this->curl, CURLOPT_POST, 1);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, "url=$postvar");
        curl_exec($this->curl);
    }

    /**
     * returns true if a protocol is used in url
     * <p>Exmpl.</p>
     * <code>
     * $crl->hasProtocol("http://www.google.com");
     * => returns true
     * $crl->hasProtocol("www.google.com");
     * => returns false
     * </code>
     * @param  String  $url
     * @return Boolean
     */
    public function hasProtocol( $url)
    {
        return strpos($url, "//");
    }

    /**
     * returns the domain from url
     * @param  String $url
     * @return String
     */
    public function getDomain( $url)
    {
        return substr($url, 0, strrpos($url, "/"));
    }

    /**
     * convert link
     * @param  type $domain
     * @param  type $url
     * @param  type $link
     * @return type
     */
    public function convertLink($domain,  $url, $link)
    {
        if ($this->hasProtocol($link)) {
            return $link;
        } elseif (($link == '#') || ($link == "/")) {
            return $url->getUrl();
        }
        //else if((strpos($link,'/'))==0)
        else if (substr($link, 0, 1) == "/") {
            return $domain . $link;
        } else {
            return $domain . "/" . $link;
        }
    }

    /**
     * Gets the page size
     * @param  String $url
     * @return Int
     */
    public function getPageSize(\Crawler\Entities\Links $url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url->getUrl());
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($this->curl);

        return curl_getinfo($this->curl, CURLINFO_SIZE_DOWNLOAD);
    }

    /**
     * get info on your last curl request
     * @return object
     * @param  string $url
     */
    public function getInfo(\Crawler\Entities\Links $url)
    {
        curl_setopt($this->curl, CURLOPT_URL, $url->getUrl());
        curl_setopt($this->curl, CURLOPT_USERAGENT, $this->default_useragent);
        curl_setopt($this->curl, CURLOPT_HEADER, TRUE);
        curl_setopt($this->curl, CURLOPT_NOBODY, TRUE);
        curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, FALSE);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_COOKIEJAR, 'cookie.txt');
        $status = array();
        // preg_match('/HTTP\/.* ([0-9]+) .*/', @curl_exec($this->curl) , $status);
        curl_exec($this->curl);
        $status = curl_getinfo($this->curl);

        return curl_getinfo($this->curl);
    }

}
