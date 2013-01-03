<?php

namespace Crawler\CLI;

use Symfony\Component\Console\Input\InputArgument,
 Symfony\Component\Console\Input\InputOption,
 Symfony\Component\Console,
 Symfony\Component\Console\Input\InputInterface,
 Symfony\Component\Console\Output\OutputInterface,
 Crawler\Export as Export;

class FilterUrls extends Console\Command\Command {

protected function configure() {
$this
->setName('filterurls')
->setDescription('manipulate ')
->addOption(
'append', 'a', InputOption::VALUE_REQUIRED, 'add a string that should be in the results', 1)
->addOption(
'write', 'w', InputOption::VALUE_REQUIRED, 'write a new filtersettings file', 1)
->addOption(
'read','r', InputOption::VALUE_NONE,'if set outputs the content of the xml')
->setHelp('filter urls sets a list of strings that you want to be in the urls of the crawler.
                    if for example you only want pages from bbc, you have to make sure that "bbc.co.uk" is in your filter list.
                    if for example you only want to have the weather subdomain, you add "weather.bbc.co.uk" to the list.
                        ');


}

protected function execute(InputInterface $input, OutputInterface $output) {
    $options = $input->getOptions();
    print_r($options);
        if ($options['read'] == "1"){
            $this->outputFile($output);
        }
        else{
        foreach ($options as $key => $value){
            
            switch($key){
            case 'append':
            $this->appendConfig($value);
            $output->writeline("content appended");
            break;
            case 'write':
            $this->writeConfig($value);
            $output->writeline("content written");
            break;

            default:
            break;
            }

        }
        }
    


print_r($options);
}
protected function outputFile($output){

$file = file_get_contents(__DIR__."/../Filters/FilterExternalUrls.xml");
$output->write($file);
}

protected function writeConfig($options)
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
$dom->save(__DIR__."/../Filters/FilterExternalUrls.xml");
} catch (Exception $e) {
echo "Could not write filter file";
}
}

protected function appendConfig($options)
{
$strings = explode(",", $options);
$dom = new \DOMDocument();
$dom->load(__DIR__."/../Filters/FilterExternalUrls.xml");
$root = $dom->getElementsByTagName("FilterExternalUrls")->item(0);
foreach ($strings as $string) {
$domain = $dom->createElement("domain");
$text = $dom->createTextNode($string);
$domain->appendChild($text);
$root->appendChild($domain);
}
try {
$dom->save(__DIR__."/../Filters/FilterExternalUrls.xml");
} catch (Exception $e) {
echo "Could not write/append to filter file";
}
}

}