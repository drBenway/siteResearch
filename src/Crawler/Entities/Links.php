<?php

namespace Crawler\Entities;

/**
 * @Entity @Table(name="Links")
 */
class Links
{
    /**
     * @id @Column(type="integer") @GeneratedValue
     * unique id
     */
    protected $id;

    /**
     * @Column(type="string",unique=true)
     * url of the page
     */
    protected $url;

    /**
     * @Column(type="integer")
     * id of the page where the link was found
     */
    protected $origin;

    /**
     * @Column(type="string");
     * link already parsed?
     */
    protected $parsed = '';

    /**
     * @Column(type="integer");
     * httpheader returned by the link
     */
    protected $header;

    /**
     * @Column(type="boolean");
     * indicates whether the page has been found or an error was returned
     */
    protected $found;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getOrigin()
    {
        return $this->origin;
    }

    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    public function getParsed()
    {
        return $this->parsed;
    }

    public function setParsed($parsed)
    {
        $this->parsed = $parsed;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function setHeader($header)
    {
        $this->header = $header;
    }

    public function getFound()
    {
        return $this->found;
    }

    public function setFound($found)
    {
        $this->found = $found;
    }

}
