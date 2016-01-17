<?php
namespace mtoolkit\entity\model\role;

class Role implements ReadableRole
{
    /**
     * @var int
     */
    private $id=-1;

    /**
     * @var string
     */
    private $name="";

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Role
     */
    public function setId($id)
    {
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
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }


}