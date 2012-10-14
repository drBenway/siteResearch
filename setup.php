<?php

namespace siteResearch;
/**
 * @package siteResearch
 * @author Yves Peeters
 */
include "config/DataBaseSetup.php";

/**
 * get mysql settings from config.ini file
 */


$mysqlsetup = parse_ini_file("config/config.ini", true);

/**
 * test for extension=php_openssl.dll
 */
if (!extension_loaded('openssl')) {
    exit("enable openssl in php.ini");
}


/**
 * make mysql connection
 */
try {
    $db = new DataBaseSetup($mysqlsetup["mysql_admin"]["usr"],
                    $mysqlsetup["mysql_admin"]["pass"],
                    $mysqlsetup["mysql_database"]["hostname"]);

} catch (PDOException $err) {
    echo 'failed to connect to mysql with user  $mysqlsetup["mysql_admin"]["usr"],
        pas: $mysqlsetup["mysql_admin"]["pass"] and host:
        $mysqlsetup["mysql_database"]["hostname"]', $err->getMessage(), "\n";
}

/**
 * create the database and select it
 */

try {
    $db->createDatabase($mysqlsetup["mysql_database"]["database"]);
    $db->selectDatabase($mysqlsetup["mysql_database"]["database"]);
} catch (PDOException $err) {
    echo 'failed to create database: ', $err->getMessage(), "\n";
}


/**
 * create a dedicated user
 */
try {
    $db->createUser($mysqlsetup["mysql_database"]["usr"], $mysqlsetup["mysql_database"]["pass"], $mysqlsetup["mysql_database"]["database"]);
} catch (PDOException $err) {
    echo 'failed to create user: ', $err->getMessage(), "\n";
}

/**
 * create the crawler table
 */
try {
    $db->createTable();
} catch (PDOException $err) {
    echo 'failed to create table Crawler: ', $err->getMessage(), "\n";
}
