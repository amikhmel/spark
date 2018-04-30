<?php
/**
 * @author advisor
 *
 */
class View {

    public $vars = array();
    protected $templatePathPrefix = '';
    
    
    public function __construct($templatePathPrefix = '') {
        $this->setTemplatesPathPrefix($templatePathPrefix);
    }
    
    public function setTemplatesPathPrefix($templatePathPrefix) {
        if(strlen($templatePathPrefix) > 0){
            $this->templatePathPrefix = $templatePathPrefix.DIRECTORY_SEPARATOR;
        } else {
            $this->templatePathPrefix = '';
        }
    }
    
    public function getTemplatesPathPrefix() {
        return $this->templatePathPrefix;
    }
    
    public function assign($varName, $value) {
        $this->vars[$varName] = $value;
    }
    
    public function clear($varName = null) {
        if ($varName === null) {
            $this->vars = array();
        } else {
            if (isset($this->vars[$varName])) {
                unset($this->vars[$varName]);
            }
        }
    }
    

    
    public function display($template) {
        echo $this->render($template);
    }

    public function render($template) {
        //exporting vars
        foreach ( $this->vars as $varName => $value ) {
            $$varName = $value;
        }
        
        $templateFileName = Bootstrap::getFilePath($this->getTemplatesPathPrefix().$template);
        $__content = '';
        if (file_exists($templateFileName)) {
            Bootstrap::saveIncludePath();
            Bootstrap::appendIncludePath(dirname($templateFileName));
            ob_start();
            include $templateFileName;
            $__content = ob_get_contents();
            ob_end_clean();
            Bootstrap::restoreIncludePath();
        } else {
            throw new Exception("No such template found at $templateFileName");
        }
        return $__content;
    
    }
    
}