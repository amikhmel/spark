<?php
/**
 * @author advisor
 *
 */
class Router {
	
	public $routes = array();

	public function __construct() {
		$defaultRoute = array( 'controller' => 'controller', 'action' => 'action' );
		$this->addRoute( $defaultRoute );
	}

	public function setRoutes($routes) {
		if (is_array( $routes )) {
			foreach ( $routes as $route ) {
				$this->addRoute();
			}
		}
		return $this;
	}

	public function getRoutes() {
		return $this->routes;
	}

	public function addRoute($route) {
		if (is_array( $route ) && isset( $route ['controller'] ) && isset( $route ['action'] )) {
			$this->routes [] = $route;
		}
	}

	public function getControllerProperty($propertyName) {
		$propertyValue = null;
		$routes = $this->getRoutes();
		
		foreach ( $routes as $route ) {
			$propertyHolder = $route [$propertyName];
			if (isset( $_REQUEST [$propertyHolder] )) {
				$propertyValue = $_REQUEST [$propertyHolder];
				break;
			}
		}
		
		return $propertyValue;
	}

	public function getControllerClass() {
		$controllerClass = $this->getControllerProperty( 'controller' );
		if ($controllerClass === null) {
			$controllerClass = $this->getDefaultControllerClass();
		}
		
		return 'Controller_' . ucfirst( $controllerClass );
	}

	public function getControllerAction() {
		$controllerAction = $this->getControllerProperty( 'action' );
		if ($controllerAction === null) {
			$controllerAction = $this->getDefaultControllerAction();
		}
		return $controllerAction;
	}

	public function getDefaultControllerClass() {
		return 'Default';
	}

	public function getDefaultControllerAction() {
		return 'default';
	}

}