<?php

/**
 * helper class for crawler, takes care of querying databaseresults
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\DB;

use PDO;

/**
 * Class to help with read/write crawler database
 */
class CrawlerResultsDB extends DBPDO implements DatabaseResultInterface
{
    /**
     * constructor
     */
    public function __construct()
    {
        try {
        $mysqlsetup = parse_ini_file(__DIR__."/../../config/config.ini", true);

        parent::__construct(
                $mysqlsetup["mysql_database"]["usr"], $mysqlsetup["mysql_database"]["pass"], $mysqlsetup["mysql_database"]["hostname"], $mysqlsetup["mysql_database"]["database"]);
        $this->selectDatabase($mysqlsetup["mysql_database"]["database"]);
    } catch (Exception $e) {
        echo "Could not connect to the database";
    }

    }

    /**
     * selects a given database
     * @param String $databasename
     */
    private function selectDatabase($databasename)
    {
        $query = "use " . $databasename;
        $this->exec($query);
    }

    /**
     * returns a list of all urls with header 301
     * @return array
     */
    public function getRedirectedUrls()
    {
        $query = "select url from crawler where header = '301'";
        $stmt = $this->query($query);
        $return = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($return, $obj->url);
        }

        return $return;
    }

    /**
     * returns the entire table content
     * @return object
     */
    public function getAll()
    {
        $query = "select * from crawler";
        $stmt = $this->query($query);
        $return = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($return, $obj);
        }

        return $return;
    }
    

    /**
     * returns the amount of urls in the crawler
     *
     * {@filesource}
     *
     * @return Int
     */
    public function countUrls()
    {
        $query = 'select * from crawler';
        $stmt = $this->query($query);

        return $stmt->rowCount();
    }

    /**
     * returns the amount of bad urls
     *
     * {@filesource}
     *
     * @return Int
     */
    public function countBadUrls()
    {
        $query = "select * from crawler where found='0'";
        $stmt = $this->query($query);

        return $stmt->rowCount();
    }

    /**
     * returns a list of urls that returned a wrong httpheader
     *
     * {@filesource}
     */
    public function getBadUrls()
    {
        $query = "select url from crawler where found = 0";
        $stmt = $this->query($query);
        $return = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($return, $obj->url);
        }

        return $return;
    }

    /**
     *  returns a list of urls with the page where they were found
     *
     * {@filesource}
     */
    public function getBadUrlsByReferer()
    {
        $query = "SELECT
            Maincrawler.id,
            Maincrawler.url,
            Maincrawler.origin,
            (select Subcrawler.url from
            crawler as Subcrawler
            where Subcrawler.id = Maincrawler.origin
            ) as result FROM crawler as Maincrawler where Maincrawler.found = 0";
        $stmt = $this->query($query);
        $return = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($return, array($obj->url, $obj->result));
        }

        return $return;
    }

    /**
     * Return a list with urls that returned httpresponse 200
     *
     * {@filesource}
     *
     * @return Array
     */
    public function getGoodUrls()
    {
        $query = "select url from crawler where found = 1";
        $stmt = $this->query($query);
        $return = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($return, $obj->url);
        }

        return $return;
    }

    /**
     * Return a list with urls that have httpheader 200 and contain a given keyword
     *
     * {@filesource}
     * @param  string $like
     * @return Array
     */
    public function getGoodUrlsLike($like)
    {
        $query = "select distinct url from crawler where found = 1 and url like '%$like%'";

        $stmt = $this->query($query);
        $return = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($return, $obj->url);
        }

        return $return;
    }

}
