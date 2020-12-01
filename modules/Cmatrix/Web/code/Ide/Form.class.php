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
    static $INSTANCES = [];
    protected $Url;
    
    protected $_Path;
    protected $_Config;
    protected $_Type;
    protected $_Parent;
    protected $_Styles;
    protected $_Jss;
    protected $_CacheName;
    
    private $Types = ['html','php','element','twig'];
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        
        $this->Url = $url;
        parent::__construct($url);
        
        if(CM_MODE === 'development' && !isset(self::$INSTANCES[$this->Url])){
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
    private function createCache(){
        
        $Cache = kernel\Ide\Cache::get('forms');
        $Content = file_get_contents($this->Path.'/form.'.$this->Type);
        
        $_parent = function() use(&$Content){
            if(!$this->Parent) return;
            $Content = '{% extends "'. self::get($this->Parent)->CacheName .'" %}'."\n\n" . $Content;
        };
        
        $_styles = function() use(&$Content){
            $Arr = array_map(function($value){
                return web\Resource::get($value)->Link;
            },$this->Styles);
            
            $Content = str_replace(
                '{% block blockStyles %}{% endblock %}',
                '{% block blockStyles %}'. ($this->Parent ? '{{ parent() }}' : null) . implode('',$Arr) .'{% endblock %}'
                ,$Content
            );
        };
        
        $_jss = function() use(&$Content){
            $Arr = array_map(function($value){
                return web\Resource::get($value)->Link;
            },$this->Jss);
            
            $Content = str_replace(
                '{% block blockJss %}{% endblock %}',
                '{% block blockJss %}'. ($this->Parent ? '{{ parent() }}' : null) . implode('',$Arr) .'{% endblock %}'
                ,$Content
            );
        };
        
        // --- --- --- --- --- --- ---
        $_parent();
        $_styles();
        $_jss();
        
        $Cache->putValue($this->CacheName,$Content);
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}







class __Form extends kernel\Reflection {
    static $INSTANCES = [];
    protected $Url;
    protected $Path;
    
    protected $_Json;
    protected $_Type;
    protected $_Parent;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        kernel\Kernel::get();
        
        $this->Url = $url;
        $this->Path = $this->getMyPath($this->Url);
        
        parent::__construct($url);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->Path;
            case 'Json' : return $this->getMyJson();
            case 'Type' : return $this->getMyType();
            case 'Parent' : return $this->getMyParent();
            default : return parent::__get($name);
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath($url){
        $Path = Module::get($this->Url)->Path .'/form/'. strAfter($this->Url,'/');
        if(!file_exists($Path) || !file_exists($Path .'/form.json')) throw new ex\Error('form descriptor [' .$this->Url. '] is not found.');
        return $Path;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyJson(){
        return $this->getInstanceValue('_Json',function(){
            return json_decode(file_get_contents($this->Path.'/form.json'),true);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyType(){
        return $this->getInstanceValue('_Type',function(){
            if(!isset($this->Json['type'])) throw new ex\Error('form [' .$this->Url. '] type is not defined.');
            return $this->Json['type'];
        });
    }

    // --- --- --- --- --- --- --- ---
    private function getMyParent(){
        return $this->getInstanceValue('_Parent',function(){
            if(!array_key_exists('parent',$this->Json)) throw new ex\Error('form [' .$this->Url. '] parent is not defined.');
            return $this->Json['parent'];
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function createCache(){
        $Fs = [
            'html' => function(){ },
            'twig' => function(){
                $Path = $this->Path .'/form.twig';
                if(!file_exists($Path)) throw new ex\Error('form [' .$this->Url. '] template is not defined.');
                
                $Key = $this->Url .'.twig';
                $Data = file_get_contents($Path);
                
                if($this->Parent){
                    $Data = '{% extends "'. Cache::get('forms')->getKey($this->Parent).'.twig' .'" %}'."\n" . $Data;
                }
                
                if(isset($this->Json['rearHead']) && count($this->Json['rearHead'])){
                    $Arr = array_map(function($value){
                        return ide\Resource::get($value)->Link;
                    },$this->Json['rearHead']);
                    
                    if(($Pos=strpos($Data,'parent()'))!==false){
                        $Pos1 = strpos(substr($Data,$Pos),'}}');
                        $Data = substr($Data,0,$Pos+$Pos1+2) . implode('',$Arr) . substr($Data,$Pos+$Pos1+2);
                    }
                    else{
                        $Data = str_replace('{% block blockRearHead %}{% endblock %}','{% block blockRearHead %}'. implode('',$Arr) .'{% endblock %}',$Data);
                    }
                }
                
                return [$Key,$Data];
            }
        ];
        
        if(!isset($Fs[$this->Type])) throw new ex\Error($this,'cache function [' .$this->Type. '] is not defined.');
        
        $_fun = $Fs[$this->Type];
        list($key,$value) = $_fun();
        if($key && $value) Cache::get('forms')->putValue($key,$value);
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
    
    // --- --- --- --- --- --- --- ---
    static function cache($url){
        return (new self($url))->createCache();
    }
}
?>