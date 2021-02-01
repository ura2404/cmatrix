<?php
/**
 * Class \Cmatrix\Structure\Provider\Datamodel
 * 
 * Провайдер датамодели.
 * Реализует механизм применения управляющих скриптов для провайдера БД.
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure\Provider;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

abstract class Datamodel implements \Cmatrix\Structure\iProvider {
    static $PROVIDERS = ['pgsql'];

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @param string $provider - provider name, sach as 'pgsql','mysql','sqlite3', etc
     * @return Cmatrix\Structure\iProvider - instance of Provider class
     */
    static function get($provider){
        if(!in_array($provider,self::$PROVIDERS)) throw new ex\Error('provider "' .$provider. '" is not valid.');
        
        $ClassName = '\Cmatrix\Structure\Provider\Datamodel\\' .ucfirst($provider);
        return new $ClassName();
    }
}
?>
