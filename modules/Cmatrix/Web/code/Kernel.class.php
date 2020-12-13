<?php
/**
 * Class Page
 *
 * @author ura@itx.ru
 * @version 1.0 2020-11-11
 */

namespace Cmatrix\Web;
use \Cmatrix\Kernel\Exception as ex;

class Kernel extends \Cmatrix\Kernel\Reflection {
    static $INSTANCES = [];
    
    protected $_Home;
    protected $_Config;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct('web.kernel');
    }
    
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Home'   : return $this->getMyHome();
            case 'Config' : return $this->getMyConfig();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyHome(){
        return $this->getInstanceValue('_Home',function(){
            if(!($Home = $this->Config->getValue('web/root'))) throw new ex\Error($this,'configure variable "web.root" is not defined.');
            return $Home;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Config - конфиг web-проекта
     */
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            $Path = \Cmatrix\Kernel\Ide\Part::get('Cmatrix/Web')->Path.CM_DS.'www';
            return \Cmatrix\Kernel\Config::get($Path);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}
?>