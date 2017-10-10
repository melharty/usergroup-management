<?php

namespace InterNations\UserGroupBundle\Entity;

/**
 * Roles
 */
class Roles
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $rolename;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $userid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rolename
     *
     * @param string $rolename
     *
     * @return Roles
     */
    public function setRolename($rolename)
    {
        $this->rolename = $rolename;

        return $this;
    }

    /**
     * Get rolename
     *
     * @return string
     */
    public function getRolename()
    {
        return $this->rolename;
    }

    /**
     * Add userid
     *
     * @param \InterNations\UserGroupBundle\Entity\Users $userid
     *
     * @return Roles
     */
    public function addUserid(\InterNations\UserGroupBundle\Entity\Users $userid)
    {
        $this->userid[] = $userid;

        return $this;
    }

    /**
     * Remove userid
     *
     * @param \InterNations\UserGroupBundle\Entity\Users $userid
     */
    public function removeUserid(\InterNations\UserGroupBundle\Entity\Users $userid)
    {
        $this->userid->removeElement($userid);
    }

    /**
     * Get userid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserid()
    {
        return $this->userid;
    }
}

