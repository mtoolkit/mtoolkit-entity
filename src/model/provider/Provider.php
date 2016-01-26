<?php
namespace mtoolkit\entity\model\provider;

use mtoolkit\core\MDataType;

class Provider implements ReadableProvider
{
    private $id = -1;

    /**
     * @var string
     */
    private $name = "";
    
    /**
     * Returns the provider id
     *
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
        MDataType::mustBe(array(MDataType::INT));

        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Provider
     */
    public function setName($name)
    {
        MDataType::mustBe(array(MDataType::STRING));

        $this->name = $name;
        return $this;
    }

}
