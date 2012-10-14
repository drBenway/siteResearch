<?php

/**
 * Tweak removeduplicates
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace siteResearch\Crawler\Tweaks;



class RemoveDuplicates implements TweakInterface
{
    public function tweak($urls)
    {
        return array_unique($urls);
    }

}
