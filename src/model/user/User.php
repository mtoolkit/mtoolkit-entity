<?php
namespace mtoolkit\entity\model\user;

use mtoolkit\core\MDataType;
use mtoolkit\entity\model\provider\ProviderUser;
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
    private $enabledDate;

    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var int
     */
    private $accessFailedCount;

    /**
     * @var Role[]
     */
    private $roleList = array();

    /**
     * @var ProviderUser[]
     */
    private $providerUserList = array();

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
        MDataType::mustBe(array(MDataType::INT));

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
        MDataType::mustBe(array(MDataType::BOOLEAN));

        $this->twoFactorEnabled = $twoFactorEnabled;
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
     * @return ProviderUser[]
     */
    public function getProviderUserList()
    {
        return $this->providerUserList;
    }

    /**
     * @param ProviderUser[] $providerUserList
     * @return User
     */
    public function setProviderUserList(array $providerUserList)
    {
        $this->providerUserList = $providerUserList;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEnabledDate()
    {
        return $this->enabledDate;
    }

    /**
     * @param \DateTime $enabledDate
     * @return User
     */
    public function setEnabledDate($enabledDate)
    {
        $this->enabledDate = $enabledDate;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     * @return User
     */
    public function setEnabled($enabled)
    {
        MDataType::mustBe(array(MDataType::BOOLEAN));

        $this->enabled = $enabled;
        return $this;
    }
}