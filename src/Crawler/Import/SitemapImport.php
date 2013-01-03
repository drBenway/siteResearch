<?php

namespace Crawler\Import;

use Crawler\DB as DB;

class SitemapImport extends ImportAbstract
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;

    }

    private function validFile()
    {
        $pos = strpos($this->path, ".xml");
        if ($pos>0) {
            return true;
        } else {
            return false;
        }
    }

    private function parseXML()
    {
        $linklist = array();

        $parser = simplexml_load_file($this->path);
        foreach ($parser->url as $link) {
            array_push($linklist,$link->loc);
        }

        return $linklist;
    }
    public function import()
    {
        if ($this->validFile()) {
            $linklist = $this->parseXML();
            $db = new DB\CrawlerDB();
            $db->initCrawler($this->path);
            $db->addUrls($linklist,$this->path);
            echo "imported sitemap: ". count($linklist) ." urls added \n";
        } else {
            echo "invalid file \n";
        }
    }

}
