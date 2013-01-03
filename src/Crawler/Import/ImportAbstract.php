<?php

/**
 * abstract class for exporters
 */

namespace Crawler\Import;

/**
 * abstract class for exporters
 */
abstract class ImportAbstract
{
    /**
    * @param string $filename
    */
    abstract public function __construct($filename);

    /**
    *  export results
    */
    abstract public function import();
}
