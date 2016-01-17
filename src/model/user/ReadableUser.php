<?php
namespace mtoolkit\entity\model\user;

use mtoolkit\entity\model\provider\Provider;
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
    public function getLockoutEndDateUtc();

    /**
     * @return boolean
     */
    public function isLockoutEnabled();

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
     * @return Provider[]
     */
    public function getUserLoginsList();
}