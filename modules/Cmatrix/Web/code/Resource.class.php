<?php
/**
 * Class Resource
 *
 * @author ura@itx.ru
 * @version 1.0 2020-11-11
 */

namespace Cmatrix\Web;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Resource extends kernel\Reflection{
    static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    protected $_CacheName;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        
        parent::__construct($this->Url);
        
        if(CM_MODE === 'development'){
            $this->createCache();
        }
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'CacheName' : return $this->getMyCacheName();
            default : throw new ex\Error('class "' .get_class($this). '" property "' .$name. '" is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return \Cmatrix\Web\Kernel::get()->Home .'/res/'. $this->CacheName;
        
        /*
        $Path = CM_ROOT.CM_DS.'modules'.CM_DS . $this->Url;
        if(!file_exists($Path)) throw new ex\Error('resource "'.$this->Url.'" is not defined.');
        
        $Pos = strrpos($this->Url,'.');
        $Name = substr($this->Url,0,$Pos);
        $Ext = substr($this->Url,$Pos+1);
        
        $CachName = md5($Name) .'.'. $Ext;
        
        if(CM_MODE === 'development'){
            $Cache = web\Ide\Cache::get('res');
            if(!$Cache->isExists($CachName)) $Cache->copy($CachName,$Path);
        }
        
        return \Cmatrix\Web\Kernel::get()->Home .'/res/'. $CachName;
        */
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyCacheName(){
        return $this->getInstanceValue('_CacheName',function(){
            $Pos = strrpos($this->Url,'.');
            $Name = substr($this->Url,0,$Pos);
            $Ext = substr($this->Url,$Pos+1);
            
            return md5($Name) .'.'. $Ext;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function createCache(){
        $Path = CM_ROOT.CM_DS.'modules'.CM_DS . $this->Url;
        if(!file_exists($Path)) throw new ex\Error('resource "'.$this->Url.'" is not defined.');
        
        $Cache = web\Ide\Cache::get('res');
        if(!$Cache->isExists($this->CacheName)) $Cache->copy($this->CacheName,$Path);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>