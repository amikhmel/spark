<?php
/**
 * Bootstrap class for application 
 * 
 * @author advisor
 * @version 1.0.0.0
 */


define('APPLICATION_PATH', dirname(__FILE__));
define('DEVELOPMENT_SERVER', false !== getenv('DEVELOPMENT_SERVER'));
chdir(APPLICATION_PATH);


class Bootstrap {
    
    static private $classPrefixes = array();
    static private $includePath = null;
    
    static public function init() {
        session_start();
        // set configs, request and other things
        self::registerAutoload();
        
        self::addClassPrefix('');
        ErrorHandler::setErrorHandler();
        
        
        self::addClassPrefix('Service');
        self::addClassPrefix('Libraries/Mailer');
        self::addClassPrefix('Model');
        
        
        Config::getInstance()->setConfigFileName('Config/config.php')->loadConfig();
        FrontController::getInstance()->setRouter(new Router());

        
        self::initTimeZoneSettings();
        Request::getInstance();
        
        
        
        
    }
    
    static public function initTimeZoneSettings(){
        if(!ini_get('date.timezone')){
            date_default_timezone_set(Config::getInstance()->get('date_timezone', 'America/Los_Angeles'));        
        }
    }
    
    static public function addClassPrefix($prefix){
        self::$classPrefixes[] = $prefix;
    }
    
    static public function run() {
        FrontController::getInstance()->dispatch();
    }
    
    static public function done() {
    
    }
    
    static public function registerAutoload() {
        set_include_path(APPLICATION_PATH);
        spl_autoload_register('Bootstrap::autoload');
    }
    
    static public function getFilePath($fileName) {
        return APPLICATION_PATH . DIRECTORY_SEPARATOR . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $fileName);
    }
    
    static public function getClassFilePath($className, $prefix = '') {
        $classFileName = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        if($prefix != ''){
            $prefix .= DIRECTORY_SEPARATOR;
        }
        return APPLICATION_PATH . DIRECTORY_SEPARATOR . $prefix . $classFileName;
    }
    
    static public function autoload($className) {
        foreach (self::$classPrefixes as $prefix) {
            $classFileName = Bootstrap::getClassFilePath($className, $prefix);
            if (file_exists($classFileName)) {
                require_once ($classFileName);
                break;
            }
        }
    }
    
    static public function saveIncludePath(){
        self::$includePath = get_include_path();
    }

    static public function restoreIncludePath(){
        if(self::$includePath !== null) { 
            set_include_path(self::$includePath);   
        }
    }    
    
    static public function appendIncludePath($path){
            set_include_path($path.PATH_SEPARATOR.get_include_path());
    }    
    

}