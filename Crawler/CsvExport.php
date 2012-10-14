<?php

/**
 * CSV export for crawler table
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace siteResearch\Crawler;

// Connect and query the database for the users


$export = new Classes\CSVExport("test.csv");
try {
    $export->writeCSV();
} catch (Exception $e) {
    echo $e;
}
