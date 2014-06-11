<?php

namespace Crawler\Entities;

/**
 * @Entity @Table(name="Page")
 */
class Page
{
    /**
     * @id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="integer")
     */
    protected $linkid;

    /**
     * @Column(type="blob")
     */
    protected $html;

    /**
     * @Column(type="string")
     */
    protected $md5;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLinkid()
    {
        return $this->linkid;
    }

    public function setLinkid($linkid)
    {
        $this->linkid = $linkid;
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function setHtml($html)
    {
        $this->html = $html;
    }

    public function getMd5()
    {
        return $this->md5;
    }

    public function setMd5($md5)
    {
        $this->md5 = $md5;
    }

}
