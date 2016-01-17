<?php
namespace mtoolkit\entity\model\role;

interface ReadableRole
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();
}