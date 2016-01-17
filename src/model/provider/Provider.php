<?php
namespace mtoolkit\entity\model\provider;

class Provider implements ReadableProvider
{
    private $id=-1;
    
    /**
     * @var string
     */
    private $loginProvider="";

    /**
     * @var string
     */
    private $providerKey="";

    /**
     * @var string
     */
    private $userId="";

    /**
     * @return string
     */
    public function getLoginProvider()
    {
        return $this->loginProvider;
    }

    /**
     * @param string $loginProvider
     * @return Provider
     */
    public function setLoginProvider($loginProvider)
    {
        $this->loginProvider = $loginProvider;
        return $this;
    }

    /**
     * @return string
     */
    public function getProviderKey()
    {
        return $this->providerKey;
    }

    /**
     * @param string $providerKey
     * @return Provider
     */
    public function setProviderKey($providerKey)
    {
        $this->providerKey = $providerKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     * @return Provider
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Provider
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    
}