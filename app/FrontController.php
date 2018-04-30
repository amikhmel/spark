<?php
/**
 * @author advisor
 *
 */
class FrontController {

    private static $_instance = null;

    private $_router = null;
    
    private $controllerClassName = null;
    private $controllerAction = null;
    
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
    
    public function setRouter($router) {
        if ($router instanceof Router) {
            $this->_router = $router;
        } else {
            throw new Exception('Router passed to FrontController is not an instance of or extends Router class');
        }
        return $this;
    }
    
    public function getRouter() {
        return $this->_router;
    }
    
    public function getControllerName(){
        return substr($this->controllerClassName, 11);
    }
    
    public function getControllerAction() {
        return $this->controllerAction;
    }
    
    public function dispatch() {
        $router = $this->getRouter();
        $controllerClassName = $router->getControllerClass();
        $controllerAction = $router->getControllerAction();
        $controllerActionName = $controllerAction . "Action";
        $this->controllerClassName = $controllerClassName;
        $this->controllerAction = $controllerAction;
        if (class_exists($controllerClassName)) {
            $controller = new $controllerClassName();
            if (method_exists($controller, $controllerActionName)) {
                $controller->init();
                $controller->$controllerActionName();
                $controller->done();
            } else {
                throw new Exception("Method $controllerActionName does not exist in controller $controllerClassName"." thrown at ".__FILE__.":".__LINE__);
            }
        } else {
            throw new Exception("Class $controllerClassName does not exist at " . Bootstrap::getClassFilePath($controllerClassName)." thrown at ".__FILE__.":".__LINE__);
        }
    
    }

}