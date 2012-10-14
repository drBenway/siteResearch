<?php

namespace siteResearch\Crawler\Filters;

class Delay5SecondsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Delay5Seconds
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Delay5Seconds;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Delay5Seconds::run
     * @todo Implement testRun().
     */
    public function testRun()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}
