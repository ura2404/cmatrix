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

class Form extends kernel\Reflection {
    static $C = [];
    static $INSTANCES = [];
    protected $Url;
    
    protected $_Path;
    protected $_Config;
    protected $_Type;
    protected $_Parent;
    protected $_Styles;
    protected $_Jss;
    protected $_CacheName;
    protected $_Content;
    
    private $Types = ['html','php','element','twig'];
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        
        $this->Url = $url;
        parent::__construct($url);
        
        isset(self::$C[$url]) ? null : self::$C[$url] = 0;
        if(CM_MODE === 'development' && !self::$C[$url]++){
            $this->createCache();
        }
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path'      : return $this->getMyPath();
            case 'Config'    : return $this->getMyConfig();
            case 'Type'      : return $this->getMyType();
            case 'Parent'    : return $this->getMyParent();
            case 'Styles'    : return $this->getMyStyles();
            case 'Jss'       : return $this->getMyJss();
            case 'CacheName' : return $this->getMyCacheName();
            case 'Content'   : return $this->getMyContent();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = kernel\Ide\Part::get($this->Url)->Path .CM_DS. kernel\Url::get($this->Url)->Path;
            if(!file_exists($Path) || !file_exists($Path .CM_DS.'config.json')) throw new ex\Error('form descriptor [' .$this->Url. '] is not found.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            return kernel\Config::get($this->Url .CM_DS. 'config.json');
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyType(){
        return $this->getInstanceValue('_Type',function(){
            if(($Type = $this->Config->getValue('form/type'))===false) throw new ex\Error('form "' .$this->Url. '" type is not defined.');
            if(!in_array($Type,$this->Types)) throw new ex\Error('form "' .$this->Url. '" type "' .$Type. '"is not valid.');
            return $Type;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyParent(){
        return $this->getInstanceValue('_Parent',function(){
            if(($Parent = $this->Config->getValue('form/parent'))===false) throw new ex\Error('form "' .$this->Url. '" parent is not defined.');
            return $Parent;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyStyles(){
        return $this->getInstanceValue('_Styles',function(){
            if(($Styles = $this->Config->getValue('form/styles'))===false) throw new ex\Error('form "' .$this->Url. '" styles is not defined.');
            return is_array($Styles) ? $Styles : [];
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyJss(){
        return $this->getInstanceValue('_Jss',function(){
            if(($Jss = $this->Config->getValue('form/jss'))===false) throw new ex\Error('form "' .$this->Url. '" jss is not defined.');
            return is_array($Jss) ? $Jss : [];
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyCacheName(){
        return $this->getInstanceValue('_CacheName',function(){
            return md5($this->Url.'/form') .'.'. $this->Type;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyContent(){
        return $this->getInstanceValue('_Content',function(){
            $Path = $this->Path.'/form.'.$this->Type;
            if(!file_exists($Path)) throw new ex\Error('form [' .$this->Url. '] template is not found.');
            return file_get_contents($Path);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function createCache(){
        //dump($this->Url,'create form cache');
        
        $_parent = function(){
            if(!$this->Parent) return;
            $this->Content = '{% extends "'. self::get($this->Parent)->CacheName .'" %}'."\n\n" . $this->Content;
        };
        
        $_styles = function(){
            //dump($this->Styles,'styles for cache');
            $Arr = array_map(function($value){
                return web\Resource::get($value)->Link;
            },$this->Styles);
            
            $this->Content = str_replace(
                '{% block blockStyles %}{% endblock %}',
                '{% block blockStyles %}'. ($this->Parent ? '{{ parent() }}' : null) . implode("\n",$Arr) .'{% endblock %}'
                ,$this->Content
            );
        };
        
        $_jss = function(){
            $Arr = array_map(function($value){
                return web\Resource::get($value)->Link;
            },$this->Jss);
            
            $this->Content = str_replace(
                '{% block blockJss %}{% endblock %}',
                '{% block blockJss %}'. ($this->Parent ? '{{ parent() }}' : null) . implode('',$Arr) .'{% endblock %}'
                ,$this->Content
            );
        };
        
        // --- --- --- --- --- --- ---
        $_parent();
        $_styles();
        $_jss();
        
        kernel\Ide\Cache::get('forms')->updateValue($this->CacheName,$this->Content);
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>