<?php
/**
 * Class Vendor
 * 
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Kernel;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Vendor extends Reflection{
    static $INSTANCES = [];
    protected $Url;
    
    protected $_Path;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        kernel\Kernel::get();
        
        $this->Url = $url;
        
        parent::__construct($url);
        $this->Path;
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
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $ModuleName = strBefore($this->Url,'/');
            $ScriptName = strAfter($this->Url,'/');
            
            $Path = cm\Kernel\Ide\Module::get('Vendor')->Path .'/code/'. $ModuleName;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error($this,'vendor module [' .$this->Url. '] is not exists.');
            
            $Loader = $ScriptName ? $Path .'/'. $ScriptName : $Path .'.loader.php';
            if(!file_exists($Loader)) throw new ex\Error($this,'vendor module [' .$this->Url. '] loader is not defined.');
            
            require_once $Loader;
            return $Path;
        });
    }



    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * Получение параметра конфига по пути к нему
     *
     * @param null|string $path путь к параметру, если null, то возвращается весь конфиг
     */
    public function getValue($path=null,$default=null){
        if($path === null) return $this->Data;

        $_fun = function($arr,$ini) use(&$_fun,$default){
            if(count($arr)>1){
                $ind = $arr[0];
                array_shift($arr);
                return isset($ini[$ind]) ? $_fun($arr,$ini[$ind]) : $default;
            }
            else return isset($ini[$arr[0]]) ? $ini[$arr[0]] : ($default || is_array($default) ? $default : false);
        };

        return $_fun(explode("/",$path),$this->Data);
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function reg($url){
        return new self($url);
    }

}

?>