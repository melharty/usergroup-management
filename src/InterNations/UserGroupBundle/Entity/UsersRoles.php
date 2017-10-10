<?php

namespace InterNations\UserGroupBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UsersRoles
 */
class UsersRoles
{
    /**
     * @var \InterNations\UserGroupBundle\Entity\Users
     */
    private $userid;

    /**
     * @var \InterNations\UserGroupBundle\Entity\Roles
     */
    private $roleid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userid = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roleid = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set roleid
     *
     * @param \InterNations\UserGroupBundle\Entity\Roles $roleId
     *
     * @return Roles
     */
    public function setRoleId(\InterNations\UserGroupBundle\Entity\Roles $roleId)
    {
        $this->roleid = $roleId;

        return $this;
    }

    /**
     * Get roleid
     *
     * @return \InterNations\UserGroupBundle\Entity\Roles
     */
    public function getRoleId()
    {
        return $this->roleid;
    }
}
