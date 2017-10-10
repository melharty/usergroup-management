<?php

namespace InterNations\UserGroupBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UsersGroups
 */
class UsersGroups
{
    /**
     * @var \InterNations\UserGroupBundle\Entity\Users
     */
    private $userid;

    /**
     * @var \InterNations\UserGroupBundle\Entity\Groups
     */
    private $groupid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->groupid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set userid
     *
     * @param \InterNations\UserGroupBundle\Entity\Users $userId
     *
     * @return Users
     */
    public function setUserId(\InterNations\UserGroupBundle\Entity\Users $userId)
    {
        $this->userid = $userId;

        return $this;
    }

    /**
     * Get userid
     *
     * @return \InterNations\UserGroupBundle\Entity\Users
     */
    public function getUserId()
    {
        return $this->userid;
    }

    /**
     * Set groupid
     *
     * @param \InterNations\UserGroupBundle\Entity\Groups $groupId
     *
     * @return Groups
     */
    public function setGroupId(\InterNations\UserGroupBundle\Entity\Groups $groupId)
    {
        $this->groupid = $groupId;

        return $this;
    }

    /**
     * Get groupid
     *
     * @return \InterNations\UserGroupBundle\Entity\Groups
     */
    public function getGroupId()
    {
        return $this->groupid;
    }
}
