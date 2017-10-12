<?php

namespace InterNations\UserGroupBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Users
 */
class Users implements UserInterface
{
	/**
     * Returns the roles granted to the user.
     */
	public function getRoles()
    {
        return array('ROLE_USER', 'ROLE_ADMIN');
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
    	return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
    	// $this->setPassword('');
    }

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $groupid;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->groupid = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Users
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Users
     */
    public function setPassword($password)
    {
        if ($password != null && $password != '') $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Users
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Users
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Users
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set id
     *
     * @param integer $id
     *
     * @return Users
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Add groupid
     *
     * @param \InterNations\UserGroupBundle\Entity\Groups $groupid
     *
     * @return Users
     */
    public function addGroupid(\InterNations\UserGroupBundle\Entity\Groups $groupid)
    {
        $this->groupid[] = $groupid;

        return $this;
    }

    /**
     * Remove groupid
     *
     * @param \InterNations\UserGroupBundle\Entity\Groups $groupid
     */
    public function removeGroupid(\InterNations\UserGroupBundle\Entity\Groups $groupid)
    {
        $this->groupid->removeElement($groupid);
    }

    /**
     * Get groupid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroupid()
    {
        return $this->groupid;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $roleid;


    /**
     * Add roleid
     *
     * @param \InterNations\UserGroupBundle\Entity\Roles $roleid
     *
     * @return Users
     */
    public function addRoleid(\InterNations\UserGroupBundle\Entity\Roles $roleid)
    {
        $this->roleid[] = $roleid;

        return $this;
    }

    /**
     * Remove roleid
     *
     * @param \InterNations\UserGroupBundle\Entity\Roles $roleid
     */
    public function removeRoleid(\InterNations\UserGroupBundle\Entity\Roles $roleid)
    {
        $this->roleid->removeElement($roleid);
    }

    /**
     * Get roleid
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoleid()
    {
        return $this->roleid;
    }
}
