<?php
/**
 * Class Kernel
 * 
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Vendor;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Kernel {

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function reg($url){
        $ModuleName = strBefore($url,'/');
        $ScriptName = strAfter($url,'/');
        
        $Path = realpath(dirname(__FILE__)) .CM_DS. $ModuleName;
        
        $Loader = $ScriptName ? $Path .CM_DS. $ScriptName : $Path .'.loader.php';
        
        if(!file_exists($Loader)) throw new ex\Error($this,'vendor module [' .$this->Url. '] loader is not defined.');
        require_once($Loader);
    }
}
?>