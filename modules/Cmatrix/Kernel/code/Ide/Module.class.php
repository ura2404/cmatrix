<?php
/**
 * Class Module
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-01
 */
 
namespace Cmatrix\Kernel\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Module extends kernel\Reflection{
    static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Module = kernel\Url::get($this->Url)->Module;
            $Path = CM_ROOT.CM_DS. 'modules' .CM_DS. $Module;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error('Module "'. $Module .'" is not exists.');
            return $Path;
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