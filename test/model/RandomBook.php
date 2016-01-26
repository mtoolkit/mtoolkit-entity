<?php

namespace mtoolkit\entity\test\model;

class RandomBook
{

    /**
     * @return boolean
     */
    public static function getRandomBoolean()
    {
        $booleanArray = array(true, false);
        return $booleanArray[array_rand($booleanArray)];
    }

    /**
     * @return string
     */
    public static function getRandomPhoneNumber()
    {
        $separatorArray = array('', '+', ' ');
        $firstSeparator = $separatorArray[array_rand($separatorArray)];
        $secondSeparator = $separatorArray[array_rand($separatorArray)];

        return $firstSeparator . rand(1000, 9999) . $secondSeparator . rand(1000, 9999);
    }

    /**
     * @return string
     */
    public static function getRandomString(){
        return uniqid("a_");
    }

    /**
     * @return string
     */
    public static function getRandomEmail(){
        $domainArray=array('it','com','fr','eu');

        return uniqid("a_") . '@' . uniqid("a_") . '.' . $domainArray[array_rand($domainArray)];
    }

    /**
     * @return \DateTime
     */
    public static function getRandomDate(){
        return new \DateTime(date('m/d/Y', rand(1262055681, 1262055681)));
    }

}