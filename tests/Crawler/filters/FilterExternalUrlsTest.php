<?php
namespace siteResearch\Crawler\Filters;


class FilterExternalUrlsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FilterExternalUrls
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new FilterExternalUrls;
        $this->object->loadConfig("filterExternalUrls.xml");
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers FilterExternalUrls::filter
     */
    public function testFilterTrue()
    {
        $this->assertEquals(true, $this->object->filter("www.github.com"), "www.github.com is ok");
    }

    public function testFilterFalse()
    {
        $this->assertEquals(false, $this->object->filter("www.bbc.co.uk"), "www.bbc.co.uk is not ok");
    }

}
