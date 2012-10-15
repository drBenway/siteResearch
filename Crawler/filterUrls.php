<?php

namespace siteResearch\Crawler;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if (version_compare(PHP_VERSION, '5.3.0') < 0) {
    echo 'Your php version is to low!(' . phpversion() . ' You need at least version 5.3';
}
$shortopts = "";
$shortopts .= "a:"; // append everything behind a to config file
$shortopts .= "w:"; // write everything behind w to config file
$shortopts .= "r"; // echo config file
$options = getopt($shortopts);


if (array_key_exists("r", $options)) {

    echoConfig();
} elseif (array_key_exists("w", $options)) {

    writeConfig($options["w"]);
} elseif (array_key_exists("a", $options)) {

    appendConfig($options["a"]);
}

function echoConfig()
{
    $file = file_get_contents("Filters/FilterExternalUrls.xml");
    echo ($file);
}

function writeConfig($options)
{
    $strings = explode(",", $options);
    $dom = new \DOMDocument();
    $dom->formatOutput = true;
    $root = $dom->createElement("FilterExternalUrls");
    $dom->appendChild($root);
    foreach ($strings as $string) {
        $domain = $dom->createElement("domain");
        $text = $dom->createTextNode($string);
        $domain->appendChild($text);
        $root->appendChild($domain);
    }
    try {
        $dom->save("Filters/FilterExternalUrls.xml");
    } catch (Exception $e) {
        echo "Could not write file";
    }
}

function appendConfig($options)
{
    $strings = explode(",", $options);
    $dom = new \DOMDocument();
    $dom->load("Filters/FilterExternalUrls.xml");
    $root = $dom->getElementsByTagName("FilterExternalUrls")->item(0);
    foreach ($strings as $string) {
        $domain = $dom->createElement("domain");
        $text = $dom->createTextNode($string);
        $domain->appendChild($text);
        $root->appendChild($domain);
    }
    try {
        $dom->save("filters/FilterExternalUrls.xml");
    } catch (Exception $e) {
        echo "Could not write file";
    }
}
