<?php
/**
 * Class Part
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-01
 */
 
namespace Cmatrix\Kernel\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Part extends kernel\Reflection {
    //static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    protected $_Config;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
        
        //dump(self::$REFINSTANCES);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'Config' : return $this->getMyConfig();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Part = kernel\Url::get($this->Url)->Part2;
            $Path = Module::get($this->Url)->Path .CM_DS. $Part;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error('Part "'. $Part .'" is not exists.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Config - part description config
     */
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            return kernel\Config::get($this->Path);
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