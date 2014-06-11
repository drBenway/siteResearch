<?php

namespace SiteParser\Entities;

/**
 * @Entity @Table(name="Connections")
 */
class Links
{
    /**
     * @id @Column(type="integer") @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="integer")
     */
    protected $source;

    /**
     * @Column(type="integer")
     */
    protected $target;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($id)
    {
        $this->source = $id;
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function setTarget($id)
    {
        $this->target = $id;
    }

}
