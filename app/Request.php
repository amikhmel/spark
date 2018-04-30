<?php
/**
 * @author advisor
 *
 */
class Request {
	
	private static $_instance = null;
	
	private static $parsed = false;
	
	public static function getInstance() {
		if (self::$_instance == null) {
			self::$_instance = new self ();
		}
		if (get_magic_quotes_gpc ()) {
			
			foreach ( $_REQUEST as $key => $value ) {
				if (! is_array ( $value )) {
					$_REQUEST [$key] = stripslashes ( $value );
				}
			}
		}
		return self::$_instance;
	}
	
	private function __construct() {
	}
	
	private function __clone() {
	}
	
	public function value($name, $default = null) {
		$value = $default;
		if (isset ( $_REQUEST [$name] )) {
			$value = $_REQUEST [$name];
		}
		return $value;
	}
	
	public function toNumber($name, $default = null, $min = null, $max = null) {
		$value = $this->value ( $name, $default );
		if (! is_numeric ( $value ) || (is_numeric ( $min ) && ($value < $min)) || (is_numeric ( $max ) && ($value > $max))) {
			$value = $default;
		}
		return $value;
	}
	
	public function toString($name, $default = null) {
		$value = $this->value ( $name, $default );
		return ($value == $default ? $default : ( string ) $value);
	}
	
	public function method() {
		return $_SERVER ['REQUEST_METHOD'];
	}
	
	public function parseSearch() {
		
		$result = false;
		$referer = false;
		
		if (isset ( $_REQUEST ['ref'] )) {
			$referer = urldecode ( $_REQUEST ['ref'] );
		} elseif (isset ( $_SERVER ['HTTP_REFERER'] )) {
			$referer = urldecode ( $_SERVER ['HTTP_REFERER'] );
		} else {
			$referer = false;
		}
		

		return $result;
	}

}