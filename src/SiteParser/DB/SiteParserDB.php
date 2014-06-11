<?php

namespace SiteParser\DB;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

class SiteParserDB
{
    public function __construct()
    {
        $paths = array(__dir__ . "/../Entities/");
        $isDevMode = false;

        // the connection configuration
        $dbParams = array(
            'driver' => 'pdo_mysql',
            'user' => 'researcher',
            'password' => 'siteresearch',
            'dbname' => 'siteresearch'
        );

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $this->entityManager = EntityManager::create($dbParams, $config);
        $this->conn = DriverManager::getConnection($dbParams);
    }

    public function init($url)
    {

        //$this->entityManager->getConnection()->query('START TRANSACTION;SET FOREIGN_KEY_CHECKS=0;TRUNCATE Links; TRUNCATE Page;SET FOREIGN_KEY_CHECKS=1; COMMIT;');

    }

    public function extract()
    {

    }

}
