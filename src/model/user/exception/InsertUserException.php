<?php
namespace mtoolkit\entity\model\user\exception;

class InsertUserException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}