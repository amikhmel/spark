<?php
/**
 * @author advisor
 *
 */
class Http {
    

    private static $timeout = 15;
    
    private function __construct() {
    }
    
    private function __clone() {
    }
    
    static public function setTimeOut($timeout){
    	if($timeout > 0){
    		self::$timeout = $timeout;
    	}
    }
    
    static public function appendUrl($url, $parameters) {
        if ($parameters != '') {
            // Check whether url already has parameters
            $isUrlWithParameters = strpos($url, "?");
            if ($isUrlWithParameters !== false) {
                // Add supplied parameters to existing ones
                $url .= "&" . $parameters;
            } else {
                $url .= "?" . $parameters;
            }
        }
        return $url;
    
    }
    
    static public function send($url, $data = '', $method = 'POST') {
        $result = false;
        if(function_exists('curl_init')){
            $result = self::curlSend($url, $data, $method);
        } else {
            $result = self::fileGetContentsSend($url, $data, $method);
        }
        return $result;
   }

   
   
   static public function fileGetContentsSend($url, $data = '', $method = 'POST') {

        if (is_array($data)) {
            $data = http_build_query($data);
        }

        if($method == 'POST'){
            $opts = array('http' => array('method' => "$method", 'header' => 'Content-Type: application/x-www-form-urlencoded' . PHP_EOL, 'content' => $data, 'timeout' =>  self::$timeout));
        } else {
            $opts = array('http' => array('method' => "$method", 'timeout' => self::$timeout));
            if($data != ''){
                $url = self::appendUrl($url, $data);
            } 
        }    
        
        $context = stream_context_create($opts);
        
        $errorReporting = error_reporting(0);
        $displayErrors = ini_get('display_errors');
        ini_set('display_errors', 'Off');
        set_error_handler('ErrorHandler::_hideErrors');
        
        $response = @file_get_contents($url, false, $context);
        
        set_error_handler('ErrorHandler::_errorHandler');
        error_reporting($errorReporting);
        ini_set('display_errors', $displayErrors);
        
        return $response;       
       
   }
   
   static public function curlSend($url, $data = '', $method = 'POST') {
        
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        
        $handle = curl_init();
        
        if ($method == 'POST') {
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        } else {
            if($data != ''){
                $url = self::appendUrl($url, $data);
            }    
        }
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_VERBOSE, 0);
        curl_setopt($handle, CURLOPT_FAILONERROR, true);
        curl_setopt($handle, CURLOPT_FORBID_REUSE, true);
        curl_setopt($handle, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, self::$timeout);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, 0);
        
        $response = curl_exec($handle);
        curl_close($handle);
        
        return (is_string($response)) ? $response : false;
    
    }
    
    static public function get($url, $data=''){
        return self::send($url, $data, 'GET');
    }
    
    static public function post($url, $data=''){
        return self::send($url, $data, 'POST');
    }
    
}