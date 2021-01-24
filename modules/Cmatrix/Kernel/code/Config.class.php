<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel;
//use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Config extends Reflection {
    //static $INSTANCES = [];
    protected $Key;
    protected $Data;
    protected $Path;
    // --- --- --- --- --- --- --- ---
    function __construct($key,$data,$path=null){
        $this->Key = $key;
        $this->Data = $data;
        $this->Path = $path;
        
        parent::__construct($this->Key);
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
        
        //return arrayGetValue(explode("/",$path),$this->Data);
        
        $_fun = function($arr,$ini) use(&$_fun,$default){
            if(count($arr)>1){
                $ind = $arr[0];
                array_shift($arr);
                return isset($ini[$ind]) ? $_fun($arr,$ini[$ind]) : ($default ? $default : false);
            }
            else return array_key_exists($arr[0],$ini) ? $ini[$arr[0]] : ($default ? $default : false);
        };
        return $_fun(explode("/",$path),$this->Data);
    }

    // --- --- --- --- --- --- --- ---
    public function setValue($key,$value){
        $this->Data = arraySetValue($this->Data,explode("/",$key),$value);
        if($this->Path) file_put_contents($this->Path,Json::encode($this->Data));
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @param string $path - path to config file folder or config file
     * @return \Cmatrix\Config - config instance 
     */
    static function get($path){
        $Path = $path.(strpos(substr($path,strrpos($path,'/')),'.')===false ? CM_DS.'config.json' : null);
        
        //if(!file_exists($Path)) throw new ex\Error('Config file "'.strAfter($Path,CM_ROOT).'" not found.');
        if(!file_exists($Path)) throw new ex\Error('Config file "'.$Path.'" not found.');
        
        return self::reg($path,Json::decode(file_get_contents($Path)),$Path);
    }
    
    // --- --- --- --- --- --- --- ---
    static function reg($key,$data,$path=null){
        return new self($key,$data,$path);
    }
}
?>