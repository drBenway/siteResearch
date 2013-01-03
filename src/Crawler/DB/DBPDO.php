<?php

/**
 * helper class extends PDO
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\DB;

use PDO;

/**
 * extends PDO with extra methods
 */
class DBPDO extends PDO
{
    /**
     * creates a database connection
     *
     * sets error mode to exception
     * sets character set to utf8
     *
     * @param string $user
     * @param string $pas
     * @param string $host
     * @param string $database
     */
    public function __construct($user, $pas, $host, $database)
    {
        parent::__construct("mysql:dbname=$database;host=$host", $user, $pas);

        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $this->setAttribute(PDO::ATTR_PERSISTENT, true);
        parent::exec('SET CHARACTER SET utf8'); // put database in utf8 mode
        parent::exec("SET Names 'utf8'");
    }

    /**
     * method to put all the items in an array between quotes
     * @param  array     $myarray
     * @return array
     * @throws Exception
     */
    public function quoteArray($myarray)
    {
        $return = array();
        if (!is_array($myarray)) {
            throw new Exception('quoteArray expects array as parameter');
        } else {
            foreach ($myarray as $item) {
                $quoted = parent::quote($item);
                array_push($return, $quoted);
            }
        }

        return $return;
    }

    /**
     * method to obtain all the columnnames from a given table
     * @param  string $table
     * @return array
     */
    public function getColumnNames($table)
    {
        $temp = new PDO("mysql:dbname=$this->database;host=$this->hostname", $this->user, $this->password);
        $querystring = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns where TABLE_NAME ='$table'";
        $results = array();
        foreach ($temp->query($querystring) as $row) {
            array_push($results, $row['COLUMN_NAME']);
        }

        return $results;
    }

    /**
     * method that returns a list of all methods within DBPDO
     */
    public function help()
    {
        $class_methods = get_class_methods(new DBPDO());
        echo "<h1>Class Methods</h1>";
        foreach ($class_methods as $method_name) {
            echo "$method_name<br />";
        }
    }

}

/**
 * extends PDOStatement
 */
class DB_PdoStatement extends \PDOStatement
{
    /**
     * store pdostatement
     * @var type
     */
    public $dbh;

    /**
     * constructor
     * @param type $dbh
     */
    protected function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

}
