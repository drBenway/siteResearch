<?php

/**
 * helper class does database setup
 *
 * @package siteResearch_setup
 * @author Yves Peeters
 */

namespace config;

/**
 * Class to set up the database for siteResearch
 *
 */
class DataBaseSetup extends PDO
{
    /**
     * Tests if a given user exists
     *
     * Makes a select query on the mysql user tabel and checks if a user is found.
     * If no user is found an exception is thrown.
     *
     * <code>
     * example:
     * $db = new DatabaseSetup("usr","pas","localhost");
     * $exists = $db->isUser("John");
     * </code>
     * @param  string  $user user to be checked
     * @return boolean
     */
    public function isUser($user)
    {
        try {
            $sql = "SELECT * FROM `user` WHERE `User`= " . $user . " limit 1";
            $q = $this->query($sql);
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $r = $q->fetch();
        } catch (PDOException $err) {
            echo 'failed to retrieve user data: ', $err->getMessage(), "\n";
        }
        if ($r . length > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * create a mysql user
     *
     * Creates a new user and grants all privleges to the user on the provided database
     *
     * @param string $user     user to be created
     * @param string $password password for user
     * @param string $database database the user has full access to
     */
    public function createUser($user, $password, $database)
    {
        $query = "GRANT ALL PRIVILEGES ON " . $database . ".* TO " . $user . "@localhost IDENTIFIED BY '" . $password . "'";
        //$query = "CREATE USER '" . $user . "'@'localhost' IDENTIFIED BY '" . $password . "'";
        $this->exec($query);
    }

    /**
     * create an empty database
     *
     * Checks if a given database exists and if not, creates it
     *
     * @param string $database database to be created
     */
    public function createDatabase($database)
    {
        $query = "create database if not exists `" . $database . "`";
        $this->exec($query);
    }

    /**
     * grants a user access to a certain database
     *
     * @param string $user     username
     * @param string $database database nmae
     */
    public function grantUserAccessToDatabase($user, $database)
    {
        $query = "grant all on " . $database . " to '" . $user . "'@'localhost'";
        $this->exec($query);
    }

    /**
     * select a database
     *
     * @param string $databasename
     */
    public function selectDatabase($databasename)
    {
        $query = "use " . $databasename;
        $this->exec($query);
    }

    /**
     * creates a table for the crawler
     *
     * create a new tabel "crawler" with fields: id,url,parsed and header
     *
     * @return void
     */
    public function createTables()
    {
        $query = "CREATE TABLE if not exists `crawler` (
                `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `url` VARCHAR( 255 ) UNIQUE ,
                `origin` INT,
                `parsed` BOOLEAN  NULL DEFAULT '0' ,
                `header` SMALLINT( 4 )  NULL DEFAULT '0',
                `found` BOOLEAN  NULL DEFAULT '0'
                )";
        $this->exec($query);
        $query = "CREATE TABLE `crawlerhtml` (
                `id` int NOT NULL AUTO_INCREMENT primaery key,
                `urlid` int(255) NOT NULL,
                `html` blob NOT NULL
                )";
        $this->exec($query);
    }

    /**
     * removes a user
     *
     * removes all privilleges for user and drops the user from mysql
     *
     * @param string $user user to be removed
     */
    public function removeUserAndAllAccess($user)
    {
        $query = "REVOKE ALL PRIVILEGES, GRANT OPTION FROM '" . $user . "'@'localhost';
                drop '" . $user . "'@'localhost';";
        $this->exec($query);
    }

    /**
     * create a database connection
     *
     * creates a new database, sets it to use utf-8, sets errormode
     *
     * @param string $root    admin user
     * @param string $rootpas admin password
     * @param string $host
     */
    public function __construct($root, $rootpas, $host)
    {
        parent::__construct("mysql:host=$host", $root, $rootpas);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $this->setAttribute(PDO::ATTR_PERSISTENT, true);
        parent::exec('SET CHARACTER SET utf8'); // put database in utf8 mode
        parent::exec("SET Names 'utf8'");
    }

}
