<?php

/**
 * CSV export for crawler tables
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Export;

use Crawler\DB as DB;

/**
 * exports crawler table to a csv file
 * takes a filename as imput an exports the table to "output/filename.csv"
 */
class CSVExport extends ExportAbstract
{
    /**
     * holds crawerlresultdb object
     * @var CrawlerResultDB
     */
    protected $db;

    /**
     * holds list containing content of crawler table
     * @var array
     */
    protected $list;

    /**
     * filename for csv file
     * @var string
     */
    protected $filename;

    /**
     * path to output direcotry
     * @var string
     */
    protected $output = "Output/";

    /**
     * create csvexport object
     * @param string $filename
     */
    public function __construct($filename,  \Crawler\DB\DatabaseInterface $db)
    {
        $this->setFilename($filename);
        $this->setDB($db);
    }

    private function setDB($db)
    {
        $this->db = $db;
    }
    /**
     * get a listing of the crawler table
     */
    private function generateList()
    {
        $this->list = $this->db->getAll();
    }

    /**
     * csv file name
     * @param string $name
     */
    private function setFilename($name)
    {
        $this->filename = $name;
    }

    /**
     * write csv file
     */
    public function export()
    {
        $this->generateList();

        // Actually create the file
        // The w+ parameter will wipe out and overwrite any existing file with the same name
        $handle = fopen($this->filename, 'w+') or die("can't open file");

        // Write the spreadsheet column titles / labels
        fputcsv($handle, array('id', 'url', 'origin', 'parsed', 'header', 'found'));
        //print_r($this->list);
        // Write all the user records to the spreadsheet
        foreach ($this->list as $obj) {
            fputcsv($handle, array($obj->getId(), $obj->getUrl(), $obj->getOrigin(), $obj->getParsed(), $obj->getHeader(), $obj->getFound()));
        }

        // Finish writing the file
        fclose($handle);
        echo "written csv file to:".$this->filename;
    }

}
