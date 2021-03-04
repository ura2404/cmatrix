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
    //static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    protected $_Config;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'Config' : return $this->getMyConfig();
            case 'Parts' : return $this->getMyParts();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Module = kernel\Url::get($this->Url)->Part1;
            $Path = CM_ROOT.CM_DS. 'modules' .CM_DS. $Module;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error('Module "'. $Module .'" is not exists.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Config - module description config
     */
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            return kernel\Config::get($this->Path);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return array - массив parts url
     */
    private function getMyParts(){
        $Arr = scandir($this->Path);
        $Parts = array_filter($Arr,function($val){ return ($val !== '.' && $val !== '..' && is_dir($this->Path.CM_DS.$val) && file_exists($this->Path.CM_DS.$val.CM_DS.'config.json')); });
        $Parts = array_map(function($val){ return $this->Url.'/'.$val; },$Parts);
        return $Parts;
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>