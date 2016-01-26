<?php 

namespace mtoolkit\entity\model\provider;

class ProviderInfo extends Provider implements ReadableProviderInfo {
	/**
     * @var string
     */
    private $appKey = "";

    /**
     * @var string
     */
    private $secretKey = "";
    
    /**
     * @return string
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     * @param string $appKey
     * @return ProviderInfo
     */
    public function setAppKey($appKey)
    {
        $this->appKey = $appKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secretKey;
    }

    /**
     * @param string $secretKey
     * @return ProviderInfo
     */
    public function setSecretKey($secretKey)
    {
        $this->secretKey = $secretKey;
        return $this;
    }

    /**
     * @param string $name
     * @return ProviderInfo
     */
    public function setName($name)
    {
        parent::setName($name);
        return $this;
    }
}
