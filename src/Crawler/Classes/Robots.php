<?php
namespace Crawler\Classes;
include 'PHPCurlCrawler.php';
/**
 * @package siteResearch
 * @author Yves Peeters
 */

/* 
 * @todo implement feature
 * parses the robot.txt file if available

 */
class Robots {
	protected $path;
	protected $robotsFile;
    function __construct($path){
    	$this->path = $path;
    }
    
    function getRobotsTxt(){
    	
    	$curl = new PHPCurlCrawler();
    	$curl->init();
    	$robotsFile = $curl->getContent($this->path);
    	if ($robotsFile){$this->robotsFile = $robotsFile;}
    	else {$this->robotsFile = "
    	User-agent: *
		Disallow: 	";}
    }
    
    
    function parseRobotsFile(){
    	$this->robotsArray = explode ("\n",$this->robotsFile);
        $this->robotsArray = $this->trimItems();
        $this->robotsArray = $this->removeComments();
    	$this->robotsArray = $this->makeSameCase();
    	$this->divideByUserAgents();

    }
    function trimItems(){
    	$newarray = array();
       	foreach ($this->robotsArray as $itemtotrim){
    		$itemtotrim = trim ($itemtotrim);
    		array_push($newarray,$itemtotrim);
    	}
    	return $newarray;
    }
    function removeComments(){
    	$newarray = array();
        foreach ($this->robotsArray as $itemtoremovecomment){
    		$pos = strpos($itemtoremovecomment,"#");
      		if (is_integer($pos)){
      			$replacement=substr($itemtoremovecomment,0,$pos);
      			array_push($newarray, $replacement);
      		}
      		else{array_push($newarray, $itemtoremovecomment);}
    	}    	
    	return $newarray;
    }
    function makeSameCase(){
    	$newarray = array();
    	foreach ($this->robotsArray as $items){
    		$items = str_replace("User-Agent","User-agent", $items);
    		$items = str_replace("disallow","Disallow", $items);
    		array_push($newarray, $items);    		
    	} 
    	return $newarray;
    }

    function divideByUserAgents(){
    	$length = count($this->robotsArray);
        
    	$keys = array();
    	$agentsArray= array();
    	for($i=0;$i<$length; $i++){
    		$pos = strpos($this->robotsArray[$i],'User-agent');
    		if($pos !== false){
    			array_push($keys,$i);
    		}
    	}
    	$length = count($keys);
        
    	for($i=0;$i<$length;$i++){
    		$keyname= str_replace("User-agent:","",$this->robotsArray[$keys[$i]]);
                if ($i<$length -1){
    		$part = array_slice($this->robotsArray,$keys[$i],($keys[$i+1]-$keys[$i])-1);
                }
                else{
                $part = array_slice($this->robotsArray,$keys[$i],$keys[count($this->robotsArray)-1]);
                }
    		$agentsArray[$keyname]=$part;	
    	}
    	echo "agentsarray";
		print_r($agentsArray);
    	
    }
}
$robot = new Robots("http://www.bbc.co.uk/robots.txt");
$robot->getRobotsTxt();
$robot->parseRobotsFile();


?>
