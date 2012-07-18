<?php



include "../config/autoload.php";
// get mysql settings from config.ini file
$mysqlsetup  = parse_ini_file("../config/config.ini", true);

// test for extension=php_openssl.dll 
if (!extension_loaded('openssl')){
    exit ("enable openssl in php.ini");
}


// make mysql connection
try{
$db = new DataBaseSetup($mysqlsetup["mysql_admin"]["usr"],$mysqlsetup["mysql_admin"]["pass"], "localhost");
echo "connecting to mysql";

} catch (PDOException $err) {
echo 'failed to connect to mysql: ', $err->getMessage(), "\n";
}

//
try{
$db->createDatabase($mysqlsetup["mysql_database"]["database"]);
} catch (PDOException $err) {
echo 'failed to create database: ', $err->getMessage(), "\n";
}

try{
        $db->createUser($mysqlsetup["mysql_database"]["usr"], $mysqlsetup["mysql_database"]["pass"],$mysqlsetup["mysql_database"]["database"]);
    }
    catch (PDOException $err) {
        echo 'failed to create user: ', $err->getMessage(), "\n";
    }

?>
