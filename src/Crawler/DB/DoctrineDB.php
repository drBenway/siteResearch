<?php

namespace Crawler\DB;

use Crawler\Entities as Entity;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;

class DoctrineDB implements DatabaseInterface
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

    public function initCrawler($url)
    {

        $this->entityManager->getConnection()->query('START TRANSACTION;SET FOREIGN_KEY_CHECKS=0;TRUNCATE Links; TRUNCATE Page;SET FOREIGN_KEY_CHECKS=1; COMMIT;');
        $links = new Entity\Links();
        $links->setUrl($url);
        $links->setParsed("");
        $links->setOrigin(0);
        $links->setHeader(200);
        $links->setFound(true);
        $this->entityManager->persist($links);
        $this->entityManager->flush();
    }

    /**
     * adds url to database
     * @param array $urls
     * @param Link  $origin
     */
    public function addUrls($urls, $origin)
    {

            $i = 0;
            $batchsize = 20;
            foreach ($urls as $url) {
                $links = new Entity\Links();
                $links->setUrl($url);
                $links->setHeader(0);
                $links->setFound(0);
                $links->setOrigin($origin->getId());
                $this->entityManager->persist($links);
                $i++;
                if ($i % $batchsize == 0) {
                $this->entityManager->flush();
                $this->entityManager->clear();
                }

            }
             $this->entityManager->flush();
                $this->entityManager->clear();
    }

    public function getUrlId($url)
    {
        $target = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findOneBy(array('url' => $url));

        return $target->getId();
    }

    public function storeLinking($urls,$origin)
    {
        foreach ($urls as $url) {
            $id = $this->getUrlId($url);

            $linkdata = new Entity\LinkData();
            $linkdata->setLinkId($id);
            $linkdata->setOriginId($origin->getId());
            $this->entityManager->persist($linkdata);
        }
             $this->entityManager->flush();
             $this->entityManager->clear();

    }

    public function returnLinking()
    {
                $target = $this->entityManager->getRepository('Crawler\Entities\Links');

        return $target;
    }

    public function addUrlsAlreadyParsed($urls, $origin)
    {
        foreach ($urls as $url) {
                $links = new Entity\Links();
                $links->setUrl($url);
                $links->setHeader(0);
                $links->setFound(0);
                $links->setParsed("parsed");
                $links->setOrigin($origin->getId());
                $this->entityManager->persist($links);
                $this->entityManager->flush();
            }

    }

    /**
     * gets an uparsed url
     * @todo bug
     */
    public function getUrlToWorkOn()
    {
        $url = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findOneBy(array('parsed' => ''));
        $this->setUrlAsDone($url);

        return $url;
    }

    /**
     * sets a given url as parsed
     * @param string $url
     */
    public function setUrlAsDone($url)
    {

        $url->setParsed('parsed');
        $this->entityManager->persist($url);
        $this->entityManager->flush();
    }

    /**
     * get urls as array returns a list of all urls currently in the DB as an array
     */
    public function getUrlsAsArray()
    {
        $temp =Array();

        $urls = $this->entityManager->getRepository('Crawler\Entities\Links')->findAll();
        foreach ($urls as $url) {
            array_push($temp, $url->getUrl());
        }

        return array_unique($temp);
    }

    /**
     * get a list of all urls that are unparsed
     * @return Array Array of Link objects
     */
    public function getUrlsToDo()
    {
        $repo = $this->entityManager->getRepository('Crawler\Entities\Links');
        $urls = $repo->findBy(array('parsed' => ''));

        return $urls;
    }

    /**
     * obtain a list of all parsed urls
     * @return Array Array of Link objects
     */
    public function getUrlsDone()
    {

        $donevalue = "parsed";
        $urls = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findBy(array('parsed' => $donevalue));

        return $urls;
    }

    /**
     * set a url to bad (expl error 404)
     * @param Link $url
     */
    public function setUrlAsBad($url)
    {

        $this->entityManager->persist($url);
        $this->entityManager->flush();
    }

    /**
     * set url as valid (http header 200)
     * @param Link $url
     */
    public function setUrlAsGood($url)
    {

        $url->setHeader(200);
        $this->entityManager->persist($url);
        $this->entityManager->flush();
    }

    /**
     * store the settings for the url
     * @param fLink $url
     */
    public function saveUrl($url)
    {
        $this->entityManager->persist($url);
        $this->entityManager->flush();
    }

    /**
     * store the responsheader for a given url
     * @param string $header
     * @param string $url
     */
    public function setResponsHeader($header, $url)
    {
        $url = $this->entityManager->getRepository('Crawler\Entities\Links')
                ->findOneBy(array('url' => $url));
        $url->setHeader($header);
        $this->entityManager->persist($url);
        $this->entityManager->flush();
    }

    public function saveHTML($url, $html, $md5)
    {
        $page = new Entity\Page();
        $page->setLinkid($url->getId());
        $page->setHtml($html);
        $page->setMd5($md5);
        $this->entityManager->persist($page);
        $this->entityManager->flush();
    }

}
