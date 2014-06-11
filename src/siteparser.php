<?php
/**
 * alternative console with stmphony
 */
namespace SiteParser;
$loader = __DIR__.'/../vendor/autoload.php';
require_once $loader;
use Crawler\CLI as CMDLine;

use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new CMDLine\GetLinksFromPages);
//$application->add(new CMDLine\ValidateHtml);
//$application->add(new CMDLine\CreateLinkDatabase);

//$application->add(new CMDLine\ExtractMetadata);
//$application->add(new CMDLine\EXtractImages);
//$application->add(new CMDLine\SEO);
//$application->add(new CMDLine\CalculatePagesize);
$application->run();
