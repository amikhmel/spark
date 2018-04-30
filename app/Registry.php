<?php
/**
 * @author advisor
 *
 */
class Registry {
    private static $storage = array();
    
    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function __construct() {
    }
    
    private function __clone() {
    }
    
    public static function set($key, $value){
        self::$storage[$key] = $value;
    }
    
    public static function get($key, $checkOnly = false){
        if(isset(self::$storage[$key])){
            return self::$storage[$key];
        } else {
            if($checkOnly){
                return null;
            } else {
                throw new Exception('No such key stored in Registry: '.$key);
            }    
        }
    }
    
}