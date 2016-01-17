<?php
namespace mtoolkit\entity\model\user;

use mtoolkit\entity\model\provider\Provider;
use mtoolkit\entity\model\role\Role;

class User implements ReadableUser
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $phoneNumber;

    /**
     * @var bool
     */
    private $twoFactorEnabled;

    /**
     * @var \DateTime
     */
    private $lockoutEndDateUtc;

    /**
     * @var bool
     */
    private $lockoutEnabled;

    /**
     * @var int
     */
    private $accessFailedCount;

    /**
     * @var Role[]
     */
    private $roleList=array();

    /**
     * @var Provider[]
     */
    private $userLoginsList=array();

    /**
     * @var string
     */
    private $userName;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $phoneNumber
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isTwoFactorEnabled()
    {
        return $this->twoFactorEnabled;
    }

    /**
     * @param boolean $twoFactorEnabled
     * @return User
     */
    public function setTwoFactorEnabled($twoFactorEnabled)
    {
        $this->twoFactorEnabled = $twoFactorEnabled;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLockoutEndDateUtc()
    {
        return $this->lockoutEndDateUtc;
    }

    /**
     * @param \DateTime $lockoutEndDateUtc
     * @return User
     */
    public function setLockoutEndDateUtc($lockoutEndDateUtc)
    {
        $this->lockoutEndDateUtc = $lockoutEndDateUtc;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isLockoutEnabled()
    {
        return $this->lockoutEnabled;
    }

    /**
     * @param boolean $lockoutEnabled
     * @return User
     */
    public function setLockoutEnabled($lockoutEnabled)
    {
        $this->lockoutEnabled = $lockoutEnabled;
        return $this;
    }

    /**
     * @return int
     */
    public function getAccessFailedCount()
    {
        return $this->accessFailedCount;
    }

    /**
     * @param int $accessFailedCount
     * @return User
     */
    public function setAccessFailedCount($accessFailedCount)
    {
        $this->accessFailedCount = $accessFailedCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     * @return User
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
        return $this;
    }

    /**
     * @return Role[]
     */
    public function getRoleList()
    {
        return $this->roleList;
    }

    /**
     * @param array $roleList
     * @return User
     */
    public function setRoleList(array $roleList)
    {
        $this->roleList = $roleList;
        return $this;
    }

    /**
     * @return Provider[]
     */
    public function getUserLoginsList()
    {
        return $this->userLoginsList;
    }

    /**
     * @param array $userLoginsList
     * @return User
     */
    public function setUserLoginsList(array $userLoginsList)
    {
        $this->userLoginsList = $userLoginsList;
        return $this;
    }


}