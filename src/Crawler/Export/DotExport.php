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
 * exports crawler table to a dot file (GraphViz)
 */
class DotExport extends ExportAbstract {

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
    public function __construct($filename) {
        $this->db = new DB\CrawlerResultsDB();
        $this->setFilename($filename);
    }

    /**
     * get a listing of the crawler table
     */
    private function generateList() {
        $this->list = $this->db->getAll();
    }

    /**
     * csv file name
     * @param string $name
     */
    private function setFilename($name) {
        $this->filename = $name;
    }

    /**
     * write csv file
     */
    public function export() {
        $this->generateList();

        $xmlstring = "<?xml version='1.0'?><root></root>";
        $xml = simplexml_load_string($xmlstring);

        $id = 1;
        foreach ($this->list as $obj) {
            $parsedurl = parse_url($obj->url);
            $parts = explode("/", $parsedurl['path']);
            $xmltree = $xml;
            foreach ($parts as $part) {

                if ($part != '') {
                    if (!isset($xmltree->$part)) {
                        try{
                        $xmltree->addChild("$part");
                        $xmltree = $xmltree->$part;
                        $xmltree->addAttribute('id', $id);
                        $id++;
                        }
                        catch (Exception $e){
                            echo "part: ".$part;
                            echo "----------------";
                            echo $e->getMessage();
                            
                        }
                    } else {
                        $mynode = $xmltree->$part;
                        if (!isset($mynode['id'])) {
                            $mynode->addAttribute('id', $id);
                            $id++;
                        }
                        $xmltree = $mynode;
                    }
                }
            }
        }

        try {
            //$it= simplexml_load_string($xml);

            global $output;
            $output = "digraph G{\n";
            $output .= "orientation=landscape\n";
            $output .= "size = \"auto\"\n";
            //$output .= "rankdir=LR";
            $output .= "page\"16.5,11.7\"\n";// A3, only for ps files
            $output .= "node [shape=box color=\"#9ACEEB\"]\n";
            $output .= "edge [color=\"#FF00FF\"]\n";

            function outputDot($xml) {
                global $output;
                foreach ($xml as $node) {
                    $output.= $node['id'] . " [label=\"" . $node->getName() . "\"]\n";
                    if ($node->count() > 0) {
                        $children = $node->children();
                        foreach ($children as $child) {
                            $output.= $node['id'] . " -> " . $child['id'] . "\n";
                        }
                    }
                    outputDot($node);
                }
            }

            $output .= outputDot($xml);
            $output .="}\n";


            $fp = fopen($this->filename, 'w');
            fwrite($fp, $output);

            fclose($fp);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
