<?php
/**
 * Class Cache
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */
 
namespace Cmatrix\Web\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Cache extends kernel\Reflection{
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
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = CM_ROOT .CM_DS. '.cache' .CM_DS. $this->Url;
            
            if(!file_exists($Path)){
                $old = umask(0);
                mkdir($Path,0770,true);
                chown($Path,'www-data');
                chgrp($Path,'www-data');
                umask($old);
            }
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    public function clear(){
        $_rec = function($path) use(&$_rec){
            $Files = array_diff(scandir($path),['.','..']);
            array_map(function($value) use($path,&$_rec){
                $Path = $path .'/'. $value;
                is_dir($Path) ? $_rec($Path) : unlink($Path);
            },$Files);
        };
        
        $_rec($this->Path);
    }
    
    // --- --- --- --- --- --- --- ---
    public function getKey($key){
        return str_replace('/','^',$key);
    }
    // --- --- --- --- --- --- --- ---
    public function getPath($key){
        $Path = $this->Path .'/'. $this->getKey($key);
        return $Path;
    }

    // --- --- --- --- --- --- --- ---
    public function putValue($key,$value){
        $Key = str_replace('/','^',$key);
        $Path = $this->Path .'/'. $Key;

        try{
            file_put_contents($Path,$value);
            chmod($Path,0660);
            chown($Path,'www-data');
            chgrp($Path,'www-data');
        }
        catch(\Throwable $e){
        }
        catch(\Exception $e){
        }
    }

    // --- --- --- --- --- --- --- ---
    public function putJsonValue($key,array $value){
        $Value = json_encode($value,
            JSON_PRETTY_PRINT             // форматирование пробелами
            | JSON_UNESCAPED_SLASHES      // не экранировать /
            | JSON_UNESCAPED_UNICODE      // не кодировать текст
        );
        
        $this->putValue($key,$Value);
    }
    
    // --- --- --- --- --- --- --- ---
    public function getValue($key){
        $Key = str_replace('/','^',$key);
        $Path = $this->Path .'/'. $Key;
        return file_exists($Path) ? file_get_contents($Path) : false;
    }    
    
    // --- --- --- --- --- --- --- ---
    public function getJsonValue($key){
        $Value = $this->getValue($key);
        return json_decode($Value,true,
            JSON_PRETTY_PRINT             // форматирование пробелами
            | JSON_UNESCAPED_SLASHES      // не экранировать /
            | JSON_UNESCAPED_UNICODE      // не кодировать текст
        );
    }
    
    // --- --- --- --- --- --- --- ---
    public function isExists($key){
        $Key = str_replace('/','^',$key);
        $Path = $this->Path .'/'. $Key;
        return file_exists($Path);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
} 
?>