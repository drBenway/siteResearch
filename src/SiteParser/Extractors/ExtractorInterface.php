<?php
namespace SiteParser\Extractors;

interface ExtractorInterface
{
    /**
     * required method
     * @param string url
     */
    public function extract($id);
}
?>
