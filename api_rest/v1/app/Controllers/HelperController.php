<?php
/**
 * HelperController.php
 *
 * Controller allowing the use of help functions.
 *
 * @link https://developer.mozilla.org/fr/docs/Web/HTTP/Status
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;


class HelperController {

     /**
     * 
     * Method to check if a date has the right format (DD-MM-YYYY).
     * 
     * @param string $date Date to check
     * @return bool
     */
    public static function validateDateFormat(string $date)
    {
        if (!preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/",$date)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to check if a time has the right format (HH:MM:SS).
     * 
     * @param string $time Time to check
     * @return bool
     */
    public static function validateTimeFormat(string $time)
    {
        if (!preg_match("/^(0[1-9]|1[0-9]|2[0-4]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/",$time)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to check if a code day has the right format (1-6).
     * 
     * @param string $time code day to check
     * @return bool
     */
    public static function validateCodeDayFormat(string $code_day)
    {
        if (!preg_match("/^[1-7]$/",$code_day)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to check if the first time is smaller or equal to the second one.
     * 
     * @param string $firsttime First time to check
     * @param string $secondtime Second time to check
     * @return bool
     */
    public static function validateChornologicalTime(string $firsttime, string $secondtime)
    {
        $firstdate = strtotime($firsttime);
        $seconddate = strtotime($secondtime);

        if ($seconddate < $firstdate) {
           return false;
        }

        return true;
    }

    /**
     * 
     * Method to generate an api token.
     * 
     * @return string The api token
     */
    public static function generateApiToken()
    {
        return md5(microtime());
    }
}