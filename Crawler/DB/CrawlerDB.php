<?php
namespace siteResearch\Crawler\DB;

use PDO;

/**
 * helper class for crawler, does all the database work
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */



/**
 * Class to help with read/write crawler database
 */
Class CrawlerDB extends DBPDO implements DatabaseInterface {

    /**
     * constructor
     */
    public function __construct()
    {
        $mysqlsetup = parse_ini_file("../config/config.ini", true);

        parent::__construct(
                $mysqlsetup["mysql_database"]["usr"], $mysqlsetup["mysql_database"]["pass"], $mysqlsetup["mysql_database"]["hostname"], $mysqlsetup["mysql_database"]["database"]);
        $this->selectDatabase($mysqlsetup["mysql_database"]["database"]);
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
     * empty table "crawler" and insert the first url
     *
     * {@filesource}
     * @param string $url
     */
    public function initCrawler($url)
    {
        $query = ("truncate table crawler");
        $this->exec($query);
        $query = ("insert into crawler (url,origin) values ('$url','0')");
        $this->exec($query);
    }

    /**
     * adds a list of urls to the crawler table
     *
     * {@filesource}
     *
     * @param Array $urls
     * @param integer Id of the page where the urls have been found
     */
    public function addUrls($urls, $origin)
    {
        $query = "select id from crawler where url = '$origin'";
        $stmt = $this->query($query);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);
        $id = $obj->id;
        $this->beginTransaction();
        // $query = 'SELECT name, colour, calories FROM fruit WHERE calories < :calories AND colour = :colour';
        // $sth = $dbh->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $query = "insert ignore into crawler (url,origin) values (?,?)";
        $stmt = $this->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        foreach ($urls as $url) {

            $stmt->execute(array($url, $id));
            // ignore is faster than checking if each url already exists
            //$this->exec("insert ignore into crawler (url,origin) values ('$url','$id')");
        }
        $this->commit();
    }

    /**
     * returns a link that has not been crawler
     *
     * {@filesource}
     */
    public function getUrlToWorkOn()
    {
        $query = "select url from crawler where parsed = 0 limit 1";
        $stmt = $this->query($query);
        $obj = $stmt->fetch(PDO::FETCH_OBJ);
        $url = $obj->url;
        $this->setUrlAsDone($url);

        return $url;
    }

    /**
     * sets a url as done by altering the 'parsed' field to true
     *
     * {@filesource}
     * @param string $url
     */
    public function setUrlAsDone($url)
    {
        /* $query = "update crawler set parsed = 1 where url = '$url'";
          $this->exec($query); */

        $stmt = $this->prepare("update crawler set parsed = 1 where url = :url");
        $stmt->bindParam(':url', $url);
        $stmt->execute();
    }

    /**
     * returns a list of urls that need to be crawled
     *
     * {@filesource}
     */
    public function getUrlsToDo()
    {
        $query = "select url from crawler where parsed = 0";
        $stmt = $this->query($query);
        $return = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($return, $obj->url);
        }
        return $return;
    }

    /**
     * returns a list of urls that already have been crawled
     *
     * {@filesource}
     */
    public function getUrlsDone()
    {
        $query = "select url from crawler where parsed = 1";
        $stmt = $this->query($query);
        $return = array();
        while ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
            array_push($return, $obj->url);
        }
        return $return;
    }

    /**
     * Mark url as bad
     *
     * sets the found field to 0
     * {@filesource}
     *
     * @param String $url
     */
    public function setUrlAsBad($url)
    {
        $query = "update crawler set found = 0 where url = '$url'";
        $this->exec($query);
    }

    /**
     * Mark url as good
     *
     * sets the found field to 1
     * {@filesource}
     *
     * @param String $url
     */
    public function setUrlAsGood($url)
    {
        $query = "update crawler set found = 1 where url = '$url'";
        $this->exec($query);
    }

    /**
     * set the header field to a given number for a given url
     *
     * @param type $header
     * @param type $url
     */
    public function setResponsHeader($header, $url)
    {
        $query = "update crawler set header = $header where url = '$url'";
        $this->exec($query);
    }

}
