<?php
/**
 * interface for runner
 *
  * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */
namespace Crawler\Classes;

interface RunnerInterface
{
    /**
     * required method
     * @param string url
     */
    public function run(array $urls);
}
