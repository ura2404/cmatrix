<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel;
//use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Config extends Reflection {
    static $INSTANCES = [];
    protected $Key;
    protected $Data;
    
    // --- --- --- --- --- --- --- ---
    function __construct($key,$data){
        $this->Key = $key;
        $this->Data = $data;
        
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
                return isset($ini[$ind]) ? $_fun($arr,$ini[$ind]) : $default;
            }
            else return array_key_exists($arr[0],$ini) ? $ini[$arr[0]] : ($default ? $default : false);
        };
        return $_fun(explode("/",$path),$this->Data);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        $Path = CM_ROOT.CM_DS .str_replace('/',CM_DS,$url);
        
        if(!file_exists($Path)) throw new ex\Error('config file path ['.$Path.'] not found.');
        
        return self::reg($url,Json::decode(file_get_contents($Path)));
    }
    
    // --- --- --- --- --- --- --- ---
    static function reg($key,$data){
        return new self($key,$data);
    }
}
?>