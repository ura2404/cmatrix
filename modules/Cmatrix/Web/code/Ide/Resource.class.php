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

class Resource extends  kernel\Ide\Resource {
    static $C = [];
    //static $INSTANCES = [];
    
    protected $_CacheName;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        parent::__construct($url);
        
        isset(self::$C[$url]) ? null : self::$C[$url] = 0;
        if(strpos($this->Url,'raw::')===false && CM_MODE === 'development' && !self::$C[$url]++) $this->createCache();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'CacheName' : return $this->getMyCacheName();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyCacheName(){
        return $this->getInstanceValue('_CacheName',function(){
            $Name = strRBefore($this->Url,'.');
            return md5($Name) .'.'. $this->Type;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function createCache(){
        //dump($this->Url,'create resource cache');
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