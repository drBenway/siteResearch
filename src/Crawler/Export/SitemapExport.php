<?php
/**
 * @package siteResearch
 * @author Yves Peeters
 */
namespace Crawler\Export;
use Crawler\DB as DB;
use Crawler\Classes as Classes;







/**
 * Class to create a sitemap for your site
 * <code>
 * $sitemap = new Sitemap($url);
 * $sitemap->generateSitemap();
 * $sitemap->writeSitemap("sitemap.xml");
 * $submit = $sitemap->pingSearchEngines($url.'sitemap.xml');
 * foreach($submit as $searchengine){
 *     echo $searchengine[0], " -> ",$searchengine[1], "<br/>\n";
 * }
 * </code>
 * option to send a ping to searchengines, telling them you updated the sitemap
 * @author Yves Peeters
 */
class SitemapExport  extends ExportAbstract {
    private $url="";
    private $data="";
    private $xml;
    private $filename;
    private $ping=
    array(array('Google','http://www.google.com/webmasters/sitemaps/ping?sitemap='),
          array('Yahoo','http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=YahooDemo&url='),
          array('Ask','http://submissions.ask. com/ping?sitemap='),
          array('Bing','http://www.bing.com/webmaster/ping.aspx?siteMap='));

    public function __construct($filename)
    {
        $this->path= $filename;
        $this->db = new DB\CrawlerResultsDB();
        $this->generateSitemap();
    }

    /**
     * fetch your good urls from the database
     * {@source}
     */
    private function getData(){
        $this->data = $this->db->getGoodUrls();
    }

    /**
     * generate xml sitemap and store it in $xml
     * {@source}
     */
    public function generateSitemap(){
        $this->getData();
        $this->xml = "<?xml version='1.0' encoding='UTF-8'?>\n
            \t<urlset xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance'
         xsi:schemaLocation='http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd'
         xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
        foreach($this->data as $url){
            $this->xml .="\t\t<url>\n";
            $this->xml .="\t\t\t<loc>".urlencode($url)."</loc>\n";
            $this->xml .="\t\t</url>";
        }
        $this->xml .="\t</urlset>";
    }

    /**
     * Writes the sitemap file to a specific path
     * <code>
     * $sitemap->writeSitemap("sitemap.xml");
     * </code>
     * {@source}
     * @param String $path
     */
    public function export(){
        try{
        $fp = fopen($this->path, 'w');
        fwrite($fp, $this->xml);
        }
        catch (Exception $e){
            echo "failed to export sitemap";
        }
    }

    /**
     * Tell the search engines you have a new sitemap
     * <code>
     * $url = "www.mydomain.com";
     * $sitemap->pingSearchEngines($url.'sitemap.xml');
     * </code>
     * {@source}
     * @param String $url
     * @return array
     */
    public function pingSearchEngines($url){
        $return = array();
        foreach ($this->ping as $SE){
            $crl = new Classes\PHP_Curl_Crawler();
            $crl->init();
            $response = $crl->getContent($SE[1].$url);
            array_push($return,array($SE[0],$response));
            unset($crl);
        }
        return $return;
    }
}
?>
