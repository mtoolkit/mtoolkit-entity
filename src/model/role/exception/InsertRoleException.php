<?php
namespace mtoolkit\entity\model\role\exception;

class InsertRoleException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}