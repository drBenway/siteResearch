<?php

namespace Crawler\DB;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

class DoctrineResultDB implements DatabaseResultInterface
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

    /**
     * returns a list of all urls with header 301
     * @return array
     */
    public function getRedirectedUrls()
    {
        $return = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findBy(array('header' => '301'));

        return $return;
    }

    /**
     * returns the entire table content
     * @return object
     */
    public function getAll()
    {
        $return = $this->entityManager->getRepository('Crawler\Entities\Links')->findAll();

        return $return;

    }

    public function getAllLinkData()
    {
                $return = $this->entityManager->getRepository('Crawler\Entities\LinkData')->findAll();

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
        $all = $this->findAll();

        return count($all);
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
        $urls = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findBy(array('found' => '0'));

        return count($urls);
    }

    /**
     * returns a list of urls that returned a wrong httpheader
     *
     * {@filesource}
     */
    public function getBadUrls()
    {
        $urls = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findBy(array('found' => '0'));

        return $urls;
    }

    /**
     *  returns a list of urls with the page where they were found
     *
     * {@filesource}
     */
    public function getBadUrlsByReferer()
    {
        $query = "SELECT Maincrawler.url, Maincrawler.origin, (
                    SELECT Subcrawler.url
                    FROM Links AS Subcrawler
                    WHERE Subcrawler.id = Maincrawler.origin
                    ) AS pagewitherror
                    FROM Links AS Maincrawler
                    WHERE Maincrawler.header >399";

        $stmt = $this->entityManager->getConnection()->prepare($query);
        $stmt->execute();
        $return = array();

        return $stmt->fetchAll();

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
        $urls = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findBy(array('found' => '1'));

        return $urls;
    }

    /**
     * Return a list with urls that have httpheader 200 and contain a given keyword
     *
     * {@filesource}
     * @param  string $like
     * @return Array
     */
    public function getGoodUrlsLike(string $like)
    {
        $urls = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findOneBy(array('found' => '1', 'url' => $like));

        return $urls;
    }

}
