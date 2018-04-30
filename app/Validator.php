<?php
/**
 * @author advisor
 *
 */
class Validator {

    static public function isEmail($value){
        $isEmail = true;
        if(strlen($value) < 6){
            $isEmail = false;
        }
        if(!preg_match('|^[\w\.=-]+@[\w\.-]+\.[\w]{2,3}$|', $value)){
            $isEmail = false;
        }
        return $isEmail;
    } 
}