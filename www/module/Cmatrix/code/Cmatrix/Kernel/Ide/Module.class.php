<?php
/**
 * Class Module
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Module extends cm\Kernel\Reflection{
    static $INSTANCES = [];
    protected $Url;
    protected $Path;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        cm\Kernel::get();
        
        $this->Url = $this->getMyUrl($url);
        $this->Path = $this->getMyPath($this->Url);
        
        parent::__construct($url);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->Path;
            default : return parent::__get($name);
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyUrl($url){
        return strBefore($url,'/');
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPath($url){
        $Path = cm\Kernel::$HOME .'/module/'. $url;
        if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error($this,'module [' .$url. '] is not exists.');
        
        return $Path;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}

?>    
