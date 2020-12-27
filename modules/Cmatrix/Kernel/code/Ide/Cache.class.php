<?php
/**
 * Class Cache
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-11-11
 */
 
namespace Cmatrix\Kernel\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Cache extends kernel\Reflection{
    //static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Root;
    protected $_Path;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Root' : return $this->getMyRoot();
            case 'Path' : return $this->getMyPath();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function createPath($Path){
        if(!file_exists($Path)){
            $old = umask(0);
            mkdir($Path,0770,true);
            chown($Path,'www-data');
            chgrp($Path,'www-data');
            umask($old);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = $this->Root .CM_DS. $this->Url;
            $this->createPath($Path);
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyRoot(){
        return $this->getInstanceValue('_Root',function(){
            return CM_ROOT.CM_DS.'.cache';
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
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
    /**
     * @return string - converted cache key
     */
    public function getKey($key){
        return str_replace(['/','\\'],['^','^'],$key);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - path to cache content file
     */
    public function getPath($key){
        $Path = $this->Path .'/'. $this->getKey($key);
        return $Path;
    }

    // --- --- --- --- --- --- --- ---
    public function putValue($key,$value){
        $Path = $this->getPath($key);

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
    public function updateValue($key,$value){
        $Path = $this->getPath($key);
        
        if(!$this->isExists($key) || filesize($Path) != strlen($value)){
            //dump($key,'need update cache value');
            $this->putValue($key,$value);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    public function putJsonValue($key,array $value){
        $Value = Json::encode($value);
        $this->putValue($key,$Value);
    }
    
    // --- --- --- --- --- --- --- ---
    public function getValue($key){
        $Path = $this->getPath($key);
        return file_exists($Path) ? file_get_contents($Path) : false;
    }    
    
    // --- --- --- --- --- --- --- ---
    public function getJsonValue($key){
        $Value = $this->getValue($key);
        return Json::decode($Value);
    }
    
    // --- --- --- --- --- --- --- ---
    public function isExists($key){
        $Path = $this->getPath($key);
        return file_exists($Path);
    }

    // --- --- --- --- --- --- --- ---
    public function copyFile($key,$path,$_callback){
        $Path = $this->getPath($key);
        
        if($_callback instanceof \Closure){
            $Content = file_get_contents($path);
            $Content = $_callback($Content);
            file_put_contents($Path,$Content);
        }
        else copy($path,$Path);
    }
    
    // --- --- --- --- --- --- --- ---
    public function updateFile($key,$path,$_callback){
        $Path = $this->getPath($key);
        
        //dump($path);
        //dump($Path);
        //dump(filesize($path));
        //dump(filesize($Path));
        if(
            file_exists($path) && (
                !$this->isExists($key)
                || md5($path) != md5($Path)
            )
        ){
            //dump($key,'need update cache file');
            $this->copyFile($key,$path,$_callback);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
} 
?>