<?php

/**
 * ImageExtractor
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace SiteParser\Extractors;

/**
 * Analyse a single page
 *
 * @author Yves Peeters
 */
class ImageExtractor implements Extractor
{
    public function extract($db,$crwlr)
    {
        $nodes = $crwlr->filter('meta');
    }

}
