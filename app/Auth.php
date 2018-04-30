<?php
class Auth {
    static public function storeUser(){
        $_SESSION['logged'] = true;
    }
    
    static function isLogged(){
        return isset($_SESSION['logged']) && ($_SESSION['logged'] === true);
    }
    
    static function logout(){
        unset($_SESSION['logged']);
    }
}