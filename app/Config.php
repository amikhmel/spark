<?php
/**
 * @author advisor
 *
 */
class Config {

    protected static $_instance = null;

    protected $config = array();

    protected $configFileName = null;
    
    public static function getInstance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    protected function __construct() {
    }
    
    protected function __clone() {
    }
    
    public function setConfigFileName($configFileName) {
        $this->configFileName = $configFileName;
        return $this;
    }
    
    public function getConfigFileName() {
        if (null !== $this->configFileName) {
            return $this->configFileName;
        } else {
            throw new Exception('No config file specified');
        }
    }
    
    public function loadConfig() {
        $configFileName = Bootstrap::getFilePath($this->getConfigFileName());
        if (file_exists($configFileName)) {
            @include $configFileName;
            
            if (isset($config) && is_array($config)) {
                $this->config = $config;
            } else {
                throw new Exception("Config file format must be a native PHP-file with \$config variable as array present."." thrown at ".__FILE__.":".__LINE__);
            }
        } else {
            throw new Exception("No such config file at $configFileName"." thrown at ".__FILE__.":".__LINE__);
        }
        return $this;
    }
    
    public function get($name, $default = null) {
        $value = $default;
        if (isset($this->config[$name])) {
            $value = $this->config[$name];
        }
        return $value;
    }
    public function set($name, $value) {
        $this->config[$name] = $value;
        return $this;
    }
    
    public function getConfigData() {
        return $this->config;
    }
    
    public static function getConfig() {
        return self::getInstance()->loadConfig()->getConfigData();
    }
    
    public function saveConfigData($config) {
        $configVar = $this->configHolderName;
        $contents = "<?php\n " . "\$config = " . var_export($config, true) . ';';
        
        // Get full path to config file
        $configFileName = Bootstrap::getFilePath($this->getConfigFileName());
        
        /* Create folder if it doesn't exist */
        if (! @file_exists(dirname($configFileName))) {
            @mkdir(dirname($configFileName), 0777, true);
        }
        
        @file_put_contents($configFileName, $contents, LOCK_EX);
    }
    
    public static function saveConfig($config) {
        self::getInstance()->saveConfigData($config);
    }

    public function getBillingGatewayDomain(){
        $gatewayDomainFeedUrl = $this->get('feed_billing_gateway_domain');
        $gatewayUrl = Http::get($gatewayDomainFeedUrl);
        if(!$gatewayUrl){
            $gatewayUrl = str_replace('https://', 'http://', $this->get('payment_gateway_url'));
        }
        return $gatewayUrl;
    }

    public static function getDisabledMobileFileName(){
    	
    	return Bootstrap::getFilePath('Data/mobile.disabled');
    }
}