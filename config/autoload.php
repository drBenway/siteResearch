<?php

function __autoload($class) {  
    // convert namespace to full file path  
   // $class = 'classes/' . str_replace('\\', '/', $class) . '.php'; 
    //$log = new Logger ('Logs/config.txt');
    //$log->log($class . " included");
    //require_once($class);  
    echo "autoload";
        $possibilities = array(
        '../classes/'.$class.'.php',
        '../filters/'.$class.'.php');
    foreach ($possibilities as $file) {
        if (file_exists($file)) {
            require_once($file);
            return true;
        }
    }
    return false; 
}

?>
