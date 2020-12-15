<?php
/**
 * Class Form
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Form extends kernel\Ide\Form {
    static $C = [];
    static $INSTANCES = [];
    
    protected $_CacheName;
    protected $_Styles;
    protected $_Scripts;
    
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        parent::__construct($url);
        
        isset(self::$C[$url]) ? null : self::$C[$url] = 0;
        if(CM_MODE === 'development' && !self::$C[$url]++) $this->createCache();
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'CacheName' : return $this->getMyCacheName();
            case 'Styles'    : return $this->getMyStyles();
            case 'Scripts'   : return $this->getMyScripts();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - cache name of form instance in the cache
     */
    private function getMyCacheName(){
        return $this->getInstanceValue('_CacheName',function(){
            return md5($this->Url.'/form') .'.'. $this->Type;
        });
    }

    // --- --- --- --- --- --- --- ---
    /**
     * @return array - list of styles urls
     */
    private function getMyStyles(){
        return $this->getInstanceValue('_Styles',function(){
            if(($Styles = $this->Config->getValue('form/styles'))===false) throw new ex\Error('form "' .$this->Url. '" styles is not defined.');
            return is_array($Styles) ? $Styles : [];
        });
    }

    // --- --- --- --- --- --- --- ---
    /**
     * @return array - list of scripts urls
     */
    private function getMyScripts(){
        return $this->getInstanceValue('_Scripts',function(){
            if(($Styles = $this->Config->getValue('form/scripts'))===false) throw new ex\Error('form "' .$this->Url. '" styles is not defined.');
            return is_array($Styles) ? $Styles : [];
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function createCache(){
        //dump($this->Url,'create form cache');
        
        $_parent = function(){
            if(!$this->Parent) return;
            $this->Content = '{% extends "'. $this->Parent->CacheName .'" %}'."\n\n" . $this->Content;
        };
        
        $_styles = function(){
            //dump($this->Styles,'styles for cache');
            $Arr = array_map(function($value){
                return web\Resource::get($value)->HeadLink;
            },$this->Styles);
            
            $this->Content = str_replace(
                '{% block blockStyles %}{% endblock %}',
                '{% block blockStyles %}'. ($this->Parent ? '{{ parent() }}' : null) . implode("\n",$Arr) .'{% endblock %}'
                ,$this->Content
            );
        };
        
        $_scripts= function(){
            $Arr = array_map(function($value){
                return web\Resource::get($value)->HeadLink;
            },$this->Scripts);
            
            $this->Content = str_replace(
                '{% block blockJss %}{% endblock %}',
                '{% block blockJss %}'. ($this->Parent ? '{{ parent() }}' : null) . implode('',$Arr) .'{% endblock %}'
                ,$this->Content
            );
        };
        
        // --- --- --- --- --- --- ---
        $_parent();
        $_styles();
        $_scripts();
        
        web\Ide\Cache::get('forms')->updateValue($this->CacheName,$this->Content);
    }
    

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>