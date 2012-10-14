<?php
namespace siteResearch\Crawler\Export;

abstract class ExportAbstract {

   abstract public function __construct($filename);
    
   abstract public function export();

}

?>
