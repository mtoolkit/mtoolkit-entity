<?php
/**
 * Created by PhpStorm.
 * User: michelepagnin
 * Date: 26/01/16
 * Time: 19.50
 */

namespace mtoolkit\entity\test\model;


class Connection
{
    public static function get(){
        return new \PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', '123456');
    }
}