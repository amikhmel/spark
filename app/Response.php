<?php
/**
 * @author advisor
 *
 */
class Response {

    private static $_instance = null;
    
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
    
    
    static protected function sendHeaders($file, $fileName){
        $fileSize = filesize($file);
        /* Send headers */      
        header("HTTP/1.1 200 OK");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: public, must-revalidate");
        header("Pragma: hack");
        
        header("Content-Type: application/octetstream");
        header("Content-Length: $fileSize");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Transfer-Encoding: binary\n");
        
    }
    
    static public function sendFile($file, $fileName){
        if(!file_exists($file)){
            return false;
        }
        
        // Get size of file
        self::sendHeaders($file, $fileName);        
        
        // Send file
        readfile($file);
        return true;
    }
    
    static public function sendHead($file, $fileName){
        if(!file_exists($file)){
            return false;
        }
        echo filesize($file);
        // Get size of file
        //self::sendHeaders($file, $fileName);        
        return true;
    }    
    
    public function redirect($url){
        header('Location: '.$url);
        die();
    }
}