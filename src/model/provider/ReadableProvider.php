<?php
namespace mtoolkit\entity\model\provider;

interface ReadableProvider
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getId();
}