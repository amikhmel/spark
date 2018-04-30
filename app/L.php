<?php

/**
 * @author advisor
 *
 */
class L {

    private static $_instance = null;

    protected $i18n = array();

    protected $fileName = null;
    protected $language = null;
    
    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function __construct() {
    	$this->language = 'en';
    }
    
    private function __clone() {
    }
    
    public function setLanguage($language) {
        $lang = 'en';
        if($language == 'de' || $language == 'en'){
            $lang = $language;
        }
        $this->language = $lang;
        return $this;
    }
    
    public function getLanguage() {
        if (null !== $this->language) {
            return $this->language;
        } else {
            throw new Exception('No language specified');
        }
    }
    
    public function getFileName(){
        $language = $this->getLanguage();
        $fileName = Bootstrap::getFilePath('I18n/'.$language.'.php');
        return $fileName;
    }
    
    public function load() {
        $fileName = $this->getFileName();
        if (file_exists($fileName)) {
            @include $fileName;
            if (isset($i18n) && is_array($i18n)) {
                foreach($i18n as $key => $value){
                    $key = strtolower($key);
                    $this->i18n[$key] = $value;
                }
            } else {
                throw new Exception("L file format must be a native PHP-file with \$i18n variable as array present."." thrown at ".__FILE__.":".__LINE__);
            }
        } else {
            throw new Exception("No such i18n file at $fileName"." thrown at ".__FILE__.":".__LINE__);
        }
        return $this;
    }

    public function get($name){
        
        $value = $name;
        $key = strtolower($name);
        if (isset($this->i18n[$key])) {
            $value = $this->i18n[$key];
        }
        return $value;
        
    }
    
    public static function text($name) {
        return L::getInstance()->get($name);
    }

}