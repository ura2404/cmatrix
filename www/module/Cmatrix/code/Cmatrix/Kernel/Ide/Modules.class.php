<?php
/**
 * Class Modules
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-08-06
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Modules{

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'createCache' : return $this->createMyCache();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    function createMyCache(){
        $Root = kernel\Kernel::$HOME .'/module';
        $Files = array_diff(scandir($Root),['.','..']);
        $Files = array_filter($Files,function($value) use($Root){
            $Path = $Root .'/'. $value;
            return is_dir($Path) && $value{0} !== '_' ? true : false;
        });
        
        array_map(function($value){
            ide\Module::cache($value);
        },$Files);
    }
    
    // --- --- --- --- --- --- --- ---
    static function cache(){
        (new self())->createCache;
    }
}
?>