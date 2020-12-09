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
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct('web.kernel');
    }
    
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Home' : return $this->getMyHome();
            default : return parent::__get($name);
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