<?php
/**
 * Class \Cmatrix\Structure\Provider
 * 
 * @author ura@itx.ru 
 * @version 1.0 2021-02-01
 */
 
namespace Cmatrix\Structure;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

abstract class Provider implements \Cmatrix\Structure\iProvider {
    static $PROVIDERS = ['pgsql','mysql','sqlite3'];
    

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    abstract public function sqlCreateSequence(iModel $prop);
    abstract public function sqlCreateTable(iModel $model);
    abstract public function sqlCreateUniques(iModel $model);
    abstract public function getPropType(array $prop);
    abstract public function getPropDefault(iModel $model, array $prop);
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($provider){
        if(!in_array($provider,self::$PROVIDERS)) throw new ex\Error('provider "' .$provider. '" is not valid.');
        
        $ClassName = '\Cmatrix\Structure\Provider\\' .ucfirst($provider);
        return new $ClassName();
        
    }
}
?>