<?php

/**
 * Tweak removeduplicates
 *
 * @package siteResearch
 * @subpackage crawler
 * @author Yves Peeters
 */

namespace Crawler\Tweaks;

/**
 * removes duplicate array entries
 */
class removeDuplicates implements TweakInterface
{
    public function tweak($urls)
    {
        if (is_array($urls)) {
            return array_unique($urls);
        } else {
            throw new \InvalidArgumentException("removeDuplicates expects parameter to be array");
        }
    }

}
