<?php

/**
 * interface for the crawler database
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace SiteParser\DB;

interface DatabaseInterface
{
    /**
     * create table to store metadata
     */
    public function createMetaTable();

    /**
     * create table to store heading
     */
    public function createHeadingTable();

    /**
     * create table to store image data
     */
    public function createImageTable();

}
