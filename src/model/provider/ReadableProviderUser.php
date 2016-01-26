<?php
namespace mtoolkit\entity\model\provider;

interface ReadableProviderUser extends ReadableProvider
{
    /**
     * @return string
     */
    public function getProviderUserId();

    /**
     * @return int
     */
    public function getUserId();
}