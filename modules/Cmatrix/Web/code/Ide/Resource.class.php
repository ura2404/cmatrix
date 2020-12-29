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

abstract class Resource extends  kernel\Ide\Resource {
    static $C = [];
    //static $INSTANCES = [];
    
    protected $_CacheKey;
    protected $_Link;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        parent::__construct($url);
        
        isset(self::$C[$url]) ? null : self::$C[$url] = 0;
        if(strpos($this->Url,'raw::')===false && CM_MODE === 'development' && !self::$C[$url]++) $this->createCache();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'CacheKey' : return $this->getMyCacheKey();
            case 'Html' : return $this->getMyHtml();
            case 'Link' : return $this->getMyLink();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyCacheKey(){
        return $this->getInstanceValue('_CacheKey',function(){
            $Name = strRBefore($this->Url,'.');
            return md5($Name) .'.'. $this->Type;
        });
    }

    // --- --- --- --- --- --- --- ---
    /**
     * @return string - url ресурса
     */
    private function getMyLink(){
        return $this->getInstanceValue('_Link',function(){
            $Link = \Cmatrix\Web\Kernel::get()->Home .
                (
                    $this->Src === 'raw' ? 
                    strAfter($this->Path,kernel\Ide\Part::get($this->Url)->Path) 
                    : '/cache/'.$this->CacheKey
                );
            return $Link;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    abstract protected function createCache();
    
    /**
     * @return string - html строка для ресурса
     */
    abstract protected function getMyHtml();
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        // типы ресурсов с персональным классом
        $SelfTypes = ['js','css','less'];
        
        $Type = kernel\Ide\Resource::get($url)->Type;
        $ClassName = '\Cmatrix\Web\Ide\Resource\\' . (in_array($Type,$SelfTypes) ? ucfirst($Type) : 'Custom');
        return new $ClassName($url);
    }
}
?>