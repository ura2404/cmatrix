<?php
/**
 * Class Vendor
 * 
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Kernel;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Vendor extends Reflection{
    static $INSTANCES = [];
    protected $Url;
    
    protected $_Path;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        cm\Kernel::get();
        
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
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            return 'qaz';
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