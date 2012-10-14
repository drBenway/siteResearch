<?php

namespace siteResearch\Crawler\Tweaks;

/**
 * Test class for ToLowercase.
 * Generated by PHPUnit on 2012-10-09 at 15:00:52.
 */
class ToLowercaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ToLowercase
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new ToLowercase;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers ToLowercase::tweak

     */
    public function testTweak()
    {
        $testarray = array("This is great","This is NOT");
        $resultarray = array("this is great","this is not");
        $this->assertEquals($this->object->tweak($testarray),$resultarray,
                'text is in lowercase.'
        );
    }

}
