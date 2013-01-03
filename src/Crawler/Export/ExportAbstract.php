<?php

/**
 * abstract class for exporters
 */

namespace Crawler\Export;

/**
 * abstract class for exporters
 */
abstract class ExportAbstract
{
    /**
    * @param string $filename
    */
    abstract public function __construct($filename);

    /**
    *  export results
    */
    abstract public function export();
}
