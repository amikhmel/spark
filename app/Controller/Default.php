<?php
/**
 * @author advisor
 *
 */
class Controller_Default extends Controller_Abstract {
    
    public function defaultAction() {
        $view = new View('Views');
        $view->display('default.phtml');
    }
    
 

    
}