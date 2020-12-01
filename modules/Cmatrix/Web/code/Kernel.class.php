<?php
/**
 * Class Page
 *
 * @author ura@itx.ru
 * @version 1.0 2020-11-11
 */

namespace Cmatrix\Web;

class Kernel extends \Cmatrix\Kernel\Reflection {
    static $INSTANCES = [];
    
    protected $_Home;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct('web.kernel');
    }
    
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Home' : return $this->getMyHome();
            default : throw new ex\Error('class "' .get_class($this). '" property "' .$name. '" is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyHome(){
        return $this->getInstanceValue('_Home',function(){
            $Config = \Cmatrix\Kernel\Config::get('Cmatrix/Web/www/config.json');
            if(!($Home = $Config->getValue('web/root'))) throw new ex\Error($this,'configure variable "web.root" is not defined.');
            return $Home;
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