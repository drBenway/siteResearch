<?php

namespace siteResearch\Crawler;


/**
 * Runs the crawler
 * @param url for site to crawl
 */

if (version_compare(PHP_VERSION, '5.3.0') < 0) {
    echo 'Your php version is to low!(' . phpversion() . ') You need at least version 5.3';
}
require_once(__DIR__."/config/SplClassLoader.php");
$loader = new Config\SplClassLoader('siteResearch', '/Applications/MAMP/htdocs/siteResearch');
$loader->register();





$shortopts = "";
$shortopts .= "u::"; // Optional value
$shortopts .= "domain"; // These options do not accept values

$longopts = array(
    "url:", // Required value
    "tweaks::", // Optional value
    "filters::", // Optional value
);


try {
    $options = getopt($shortopts, $longopts);
} catch (Exception $e) {
    echo 'launch error: ', $e->getMessage(), "\n";
    echo "
        Launch runCrawler from the command line:
        php runCrawler.php + options.
        list of options:
        url=>'site to crawl' !mandatory option
        example: php runCrawler.php --url=\"http://www.bbc.co.uk/ \”

        filters=> filter set that you want to use
        The default filter set is filters/filters.xml
        example: php runCrawler.php --url=\"http://www.bbc.co.uk/ \” --filters=\"filters/myfilters.xml\"

";
}
if(!isset($options["url"])){
   // @todo throw exception
}

ini_set('memory_limit', '100M');
set_time_limit(1000000);
//$mypath = str_replace("runCrawler.php","",$_SERVER["PHP_SELF"]);
//set_include_path($mypath);


$domain = $options["url"];



//*** setup database ***
$db = new DB\CrawlerDB;
$db->initCrawler($domain);





//*** setup tweaks ***
// if filters argument is provided, use that file, else use the default filters/filters.xml
if (isset($options["tweaks"])) {
    $tweaks = new Tweaks\TweakRunner($options["tweaks"]);
    $tweaks->init();
} else {
    $tweaks = new Tweaks\TweakRunner('Tweaks/tweaks.xml');
    $tweaks->init();
}




//*** setup filters ***
// if filters argument is provided, use that file, else use the default filters/filters.xml
if (isset($options["filters"])) {
    $filters = new Filters\FilterRunner($options["filters"]);
    $filters->init();
} else {
    $filters = new Filters\FilterRunner('filters/filters.xml');
    $filters->init();
}


$crwlrsettings = new Classes\CrawlerSettings($domain, "domain", $db, $tweaks, $filters, true, true);

//*** setup crawler ***
echo "starting Crawler \n";
$s = microtime(true);
$scraper = new Classes\Crawler($crwlrsettings);
unset($scraper);
$e = microtime(true);
$totaltime = round($e - $s, 2) . " Seconds";

echo "finished crawling in" . $totaltime;
