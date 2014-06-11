<?php
// bootstrap.php
require_once '../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array(__dir__."/Crawler/Entities/",__dir__."/SiteParser/Entities/");
$isDevMode = false;

// the connection configuration
$dbParams = array(
    'driver'   => 'pdo_mysql',
    'user'     => 'researcher',
    'password' => 'siteresearch',
    'dbname'   => 'siteresearch',
);

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
$entityManager = EntityManager::create($dbParams, $config);
