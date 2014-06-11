<?php

namespace Crawler\Entities;

/**
 * @Entity @Table(name="LinkData")
 */
class LinkData
{
    /**
     * @id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $link;

    /**
     * @Column(type="integer")
     */
    protected $origin;

    public function setLinkId($link)
    {
        $this->link = $link;
    }
    public function getLinkId()
    {
        return $this->link;
    }

    public function setOriginId($origin)
    {
        $this->origin = $origin;
    }
    public function getOriginId()
    {
        return $this->origin;
    }
    public function getId()
    {
        return $this->id;
    }
}
