<?php
namespace mtoolkit\entity\model\user;

use mtoolkit\entity\model\provider\ProviderUser;
use mtoolkit\entity\model\role\Role;

interface ReadableUser
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return string
     */
    public function getPhoneNumber();

    /**
     * @return boolean
     */
    public function isTwoFactorEnabled();

    /**
     * @return \DateTime
     */
    public function getEnabledDate();

    /**
     * @return boolean
     */
    public function isEnabled();

    /**
     * @return int
     */
    public function getAccessFailedCount();

    /**
     * @return string
     */
    public function getUserName();

    /**
     * @return Role[]
     */
    public function getRoleList();

    /**
     * @return ProviderUser[]
     */
    public function getProviderUserList();
}