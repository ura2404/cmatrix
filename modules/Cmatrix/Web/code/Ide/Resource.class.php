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
    static $C;
    static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    protected $_Type;
    protected $_CacheName;

    private $Types = ['ico','png','jpg','css','js'];
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
        
        if(CM_MODE === 'development' && !self::$C++){
            $this->createCache();
        }
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
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
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = CM_ROOT.CM_DS. 'modules' .CM_DS. $this->Url;
            if(!file_exists($Path)) throw new ex\Error('resource file [' .$this->Url. '] is not found.');
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