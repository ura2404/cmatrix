<?php
/**
 * Class Cache
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */
 
namespace Cmatrix\Web\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Cache extends kernel\Ide\Cache {
    static $INSTANCES = [];
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Root' : return $this->getMyRoot();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyRoot(){
        return $this->getInstanceValue('_Root',function(){
            return CM_ROOT.CM_DS .'www'. CM_DS .'.cache';
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>