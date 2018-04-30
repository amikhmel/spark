<?php
/**
 * @author advisor
 *
 */
class ErrorHandler {
    
    static public function setErrorHandler() {
        if (DEVELOPMENT_SERVER) {
            ini_set('display_errors', 'On');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 'Off');
            error_reporting(0);
        }
        
        set_error_handler('ErrorHandler::_errorHandler');
        set_exception_handler('ErrorHandler::_exceptionHandler');
    }
    
    static public function _exceptionHandler($exception) {
        if (DEVELOPMENT_SERVER) {
            echo "<pre>Uncaught exception: ", $exception->getMessage(), "</pre>\n";
        }
    }
    
    static public function _errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
        if (DEVELOPMENT_SERVER) {
            echo <<<ERR
<pre>
Error [<b>$errno</b>]:$errstr
    in  $errfile at line <b>$errline</b>
</pre><br>         
ERR;
        }
        return true;
    }
    
    static public function _hideErrors($errno, $errstr, $errfile, $errline, $errcontext) {
        return true;
    }
    

}