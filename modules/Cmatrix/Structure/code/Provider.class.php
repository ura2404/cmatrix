<?php
/**
 * Class Cmatrix\Structure\Provider
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Provider {
    static $PROVIDERS = ['pgsql'];
    
    // --- --- --- --- --- --- --- ---
    function __construct($provider){
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            //case 'Sql' : return $this->getMySql();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($provider){
        if(!in_array($provider,self::$PROVIDERS)) throw new ex\Error('provider "' .$provider. '" is not valid.');
        $ClassName = '\Cmatrix\Structure\Provider\\' . ucfirst($provider);
        return new $ClassName();
    }
}
?>