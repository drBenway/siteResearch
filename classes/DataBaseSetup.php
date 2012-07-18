<?php

class DataBaseSetup extends PDO {

    /**
     * tests if a given user exists
     * @param type $user
     * @return type 
     */
    public function isUser($user) {
        try{
        $sql = "SELECT * FROM `user` WHERE `User`= ".$user." limit 1";
        $q= $this->query($sql);
        $q->setFetchMode(PDO::FETCH_ASSOC);
        $r = $q->fetch();
        } catch (PDOException $err) {
            echo 'failed to retrieve user data: ', $err->getMessage(), "\n";
        }
        if($r.length > 0){
            return true;
        }
        else{
            return false;
        }
        
    }

    /**
     * create a mysql user
     * @param type $user
     * @param type $password 
     */
    public function createUser($user, $password,$database) {
        $query="GRANT ALL PRIVILEGES ON ".$database.".* TO ".$user."@localhost IDENTIFIED BY '".$password."'"; 
        //$query = "CREATE USER '" . $user . "'@'localhost' IDENTIFIED BY '" . $password . "'";
        $this->exec($query);
    }

    /**
     * create an empty database
     * @param type $database 
     */
    public function createDatabase($database) {
        $query = "create database if not exists `" . $database . "`";
        $this->exec($query);
    }

    /**
     * grants a user access to a sertain database
     * @param type $user
     * @param type $database 
     */
    public function grantUserAccessToDatabase($user, $database) {
        $query = "grant all on " . $database . " to '" . $user . "'@'localhost'";
        $this->exec($query);
    }

    /**
     * create a new tabel "crawler" with fields: id,url,parsed and header
     */
    public function createTable() {
        $query = "CREATE TABLE if not exists `crawler` (
                `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
                `url` VARCHAR( 255 ) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin UNIQUE ,
                `origin` INT,
                `parsed` BOOLEAN NULL ,
                `header` SMALLINT( 4 ) NULL
                )";
        $dbh->exec($query);
    }

    /**
     * removes all privilleges for user,
     * drops the user from mysql
     * @param type $user 
     */
    public function removeUserAndAllAccess($user) {
        $query = "REVOKE ALL PRIVILEGES, GRANT OPTION FROM '" . $user . "'@'localhost';
                drop '" . $user . "'@'localhost';";
        $this->exec($query);
    }

    public function __construct($root, $rootpas, $host) {
        parent::__construct("mysql:host=$host", $root, $rootpas);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $this->setAttribute(PDO::ATTR_PERSISTENT, true);
        parent::exec('SET CHARACTER SET utf8'); // put database in utf8 mode
        parent::exec("SET Names 'utf8'");
    }

}

?>
