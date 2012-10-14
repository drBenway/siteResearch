<?php

/**
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace siteResearch\Crawler\Classes;

/**
 * Description of Logger
 * Class to write messages to log files
 */
Class Logger {

    private $path;
    private $message;

    public function __construct($path)
    {
        $this->path = $path;
        $this->clear();
        $this->log("Log started:" . date('y-m-d:G.i') . "\n");
        $this->store();
    }

    public function clear()
    {
        $fh = fopen($this->path, "w") or die("can't open file, can't clean log");
        fwrite($fh, "");
        fclose($fh);
    }

    public function log($msg)
    {
        $this->message = $msg;
        $this->store();
    }

    private function store()
    {
        $fh = fopen($this->path, 'a') or die("can't open file");
        fwrite($fh, $this->message . "\n");
        fclose($fh);
    }

}
