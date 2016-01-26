<?php
namespace mtoolkit\entity\model\provider;

class ProviderUser extends Provider implements ReadableProviderUser
{
    /**
     * @var int
     */
	private $userId;

    /**
     * @var string
     */
    private $providerUserId;

    /**
     * @return string
     */
    public function getProviderUserId()
    {
        return $this->providerUserId;
    }

    /**
     * @param string $providerUserId
     * @return ProviderUser
     */
    public function setProviderUserId($providerUserId)
    {
        $this->providerUserId = $providerUserId;
        return $this;
    }

    /**
     * @param int $id
     * @return ProviderUser
     */
    public function setProviderId($id){
    	parent::setId($id);
        return $this;
    }

    /**
     * @return int
     */
    public function getProviderId(){
    	return parent::getId();
    }

    /**
     * @param $name
     * @return ProviderUser
     */
    public function setProviderName($name){
    	parent::setName($name);
        return $this;
    }

    /**
     * @return string
     */
    public function getProviderName(){
    	return parent::getName();
    }

    /**
     * @return int
     */
    public function getUserId(){
    	return $this->userId;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setUserId($id){
    	$this->userId=$id;
    	return $this;
    }

    /**
     * @param string $name
     * @return ProviderUser
     */
    public function setName($name)
    {
        parent::setName($name);
        return $this;
    }
}
