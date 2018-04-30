<?php
/**
 * @author advisor
 *
 */
class Message {

    private function __construct() {
    }
    
    private function __clone() {
    }
    
    public static function addMessage($value){
        $_SESSION['messages'][] = $value;
    }
    
    public static function getMessages(){
        $messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
        unset($_SESSION['messages']);
        return $messages;
    }
}