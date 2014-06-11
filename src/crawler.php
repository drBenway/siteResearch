<?php
/**
 * alternative console with stmphony
 */
namespace Crawler;
$loader = __DIR__.'/../vendor/autoload.php';
require_once $loader;
use Crawler\CLI as CMDLine;

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new CMDLine\FromUrl);
$application->add(new CMDLine\CSVExport);
$application->add(new CMDLine\DotExport);
$application->add(new CMDLine\GEXFExport);
$application->add(new CMDLine\SitemapExport);
$application->add(new CMDLine\FromSitemap);
$application->add(new CMDLine\FilterUrls);
$application->add(new CMDLine\BrokenLinksExport);
$application->run();
