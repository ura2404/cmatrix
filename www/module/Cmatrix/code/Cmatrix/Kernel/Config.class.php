<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Config extends Reflection {
    static $INSTANCES = [];
    protected $Key;
    protected $Data;

    // --- --- --- --- --- --- --- ---
    function __construct($key,$data=null){
        Kernel::get();
        
        $this->Key = !$key ? 'app' : $key;
        $this->Data = !$data ? $this->getMyFile() : $data;
        parent::__construct($this->Key);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'File' : return $this->getMyFile();
            default : return parent::__get($name);
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        switch($this->Key){
            case 'app'   : return '/config/app.json';
            case 'pages' : return '/config/pages.json';
            default :
                return '/config/app222.json';
        }
    }

    // --- --- --- --- --- --- --- ---
    private function getMyFile(){
        $Path = $this->getMyPath();
        $Fullpath = Kernel::$HOME . $Path;

        if(!file_exists($Fullpath)) throw new ex\Error($this,'config file path ['.$Path.'] not validate.');

        return json_decode(file_get_contents($Fullpath),true);
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
    static function reg($key,$data){
        return new self($key,$data);
    }

    // --- --- --- --- --- --- --- ---
    static function get($key=null){
        return new self($key);
    }
}

?>