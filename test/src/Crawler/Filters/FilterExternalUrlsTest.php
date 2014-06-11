<?php

namespace Crawler\Filters;
 use
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\Config\FileLocator,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
/**
 * Generated by PHPUnit_SkeletonGenerator on 2013-01-23 at 18:06:00.
 */
class FilterExternalUrlsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilterRunner
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new FilterExternalUrls("FilterUrl.xml");
        
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function filterTest()
   {
        $list = array("http://www.westworld.be/index.aspx","https://www.bbc.co.uk","http://www.detijd.be/index.php");
        
        $this->AssertEquals(array("http://www.westworld.be/index.aspx"),$this->object->filter($list),"filter alle xternal urls");
    }
}
