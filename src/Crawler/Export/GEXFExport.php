<?php

/**
 * GEXF export for crawler tables
 *
 * Provides an easy way to export the crawler table into Gephi format
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Export;

use Crawler\DB as DB;

/**
 * exports crawler table to gexf file
 * takes a filename as imput an exports the table to "output/filename.gexf"
 */
class GEXFExport extends ExportAbstract
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
    public function __construct($filename , \Crawler\DB\DatabaseResultInterface $db)
    {

        $this->setFilename($filename);
        $this->setDB($db);
    }

    /**
     * csv file name
     * @param string $name
     */
    private function setFilename($name)
    {
        $this->filename = $name;
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
        $list = $this->db->getAll();

        return $list;
    }

    private function generateData()
    {
        $data = $this->db->getAllLinkData();

        return $data;
    }

    /**
     * write gefx file
     * @example gexf file
     *  <?xml version="1.0" encoding="UTF-8"?>
     *      <gexf xmlns="http://www.gexf.net/1.2draft" version="1.2">
     *          <graph mode="static" defaultedgetype="directed">
     *              <nodes>
     *                  <node id="0" label="Hello" />
     *                  <node id="1" label="Word" />
     *              </nodes>
     *              <edges>
     *                  <edge id="0" source="0" target="1" />
     *              </edges>
     *          </graph>
     *      </gexf>
     */
    public function export()
    {
        $allurls = $this->generateList();
        $linkdata = $this->generateData();

        $xml = "<?xml version='1.0' encoding='UTF-8'?>";
        $xml .="<gexf xmlns='http://www.gexf.net/1.2draft' version='1.2'>";
        $xml .="<graph mode='static' defaultedgetype='directed'>";

        /**
          $xmltree->addChild("$part");
          $xmltree = $xmltree->$part;
          $xmltree->addAttribute('id', $id);
         *
         */
        // node loop
        $xml .= "<nodes>";
        foreach ($allurls as $url) {

            $xml .= "<node id='" . $url->getId() . "' label='" . $url->getUrl() . "' />";
        };
        $xml .="</nodes>";
        $xml .="<edges>";
        // edge loop
        $edgeid = 0;
        foreach ($linkdata as $link) {
            $xml .= "<edge id='" . $link->getId() . "' source='" . $link->getOriginId() . "' target='" . $link->getLinkId() . "' />";
            $edgeid++;
        };
        $xml .="</edges></graph></gexf>";
        try {
            $fp = fopen($this->filename, 'w');
            fwrite($fp, $xml);

            fclose($fp);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}
