<?php
namespace mtoolkit\entity\model\provider\exception;

class InsertProviderException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}