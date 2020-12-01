<?php
/**
 * Class Resource
 *
 * @author ura@itx.ru
 * @version 1.0 2020-11-11
 */

namespace Cmatrix\Web\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Resource extends kernel\Reflection{
    static $INSTANCES = [];
    
    protected $Url;
    
    protected $_isRaw;
    protected $_Path;
    protected $_Type;
    protected $_CacheName;

    private $Types = ['ico','png','jpg','css','js'];
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
        
        if(!$this->isRaw && CM_MODE === 'development' && !isset(self::$INSTANCES[$this->Url])){
            $this->createCache();
        }
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'isRaw' : return $this->getMyRaw();
            case 'Path' : return $this->getMyPath();
            case 'Type' : return $this->getMyType();
            case 'CacheName' : return $this->getMyCacheName();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyRaw(){
        return $this->getInstanceValue('_isRaw',function(){
            return strStart($this->Url,'raw::') ? true : false;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            if(!$this->isRaw){
                $Path = kernel\Ide\Part::get($this->Url)->Path .CM_DS. kernel\Url::get($this->Url)->Path;
                if(!file_exists($Path)) throw new ex\Error('resource file [' .$this->Url. '] is not found.');
                dump($Path,1);
            }
            else{
                $Path = strAfter($this->Url,'raw::');
                dump($Path,2);
            }
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyType(){
        return $this->getInstanceValue('_Type',function(){
            $Type = strRAfter($this->Url,'.');
            if(!in_array($Type,$this->Types)) throw new ex\Error('resource "' .$this->Url. '" type "' .$Type. '"is not valid.');
            return $Type;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyCacheName(){
        return $this->getInstanceValue('_CacheName',function(){
            $Name = strRBefore($this->Url,'.');
            return md5($Name) .'.'. $this->Type;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function createCache(){
        $Cache = web\Ide\Cache::get('res');
        $Cache->updateFile($this->CacheName,$this->Path);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>