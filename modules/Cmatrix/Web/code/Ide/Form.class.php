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

class Form extends kernel\Reflection {
    static $INSTANCES = [];
    protected $Url;
    
    protected $_Path;
    protected $_Json;
    protected $_Type;
    protected $_Parent;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'Json' : return $this->getMyJson();
            case 'Type' : return $this->getMyType();
            case 'Parent' : return $this->getMyParent();
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
            if(!file_exists($Path) || !file_exists($Path .'/config.json')) throw new ex\Error($this,'form descriptor [' .$this->Url. '] is not found.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyJson(){
        return $this->getInstanceValue('_Json',function(){
            return json_decode(file_get_contents($this->Path.'/config.json'),true);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyType(){
        return $this->getInstanceValue('_Type',function(){
            if(!isset($this->Json['form']['type'])) throw new ex\Error($this,'form [' .$this->Url. '] type is not defined.');
            return $this->Json['form']['type'];
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyParent(){
        return $this->getInstanceValue('_Parent',function(){
            if(isset($this->Json['form']) && !array_key_exists('parent',$this->Json['form'])) throw new ex\Error($this,'form [' .$this->Url. '] parent is not defined.');
            return $this->Json['form']['parent'];
        });
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
        if(!file_exists($Path) || !file_exists($Path .'/form.json')) throw new ex\Error($this,'form descriptor [' .$this->Url. '] is not found.');
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
            if(!isset($this->Json['type'])) throw new ex\Error($this,'form [' .$this->Url. '] type is not defined.');
            return $this->Json['type'];
        });
    }

    // --- --- --- --- --- --- --- ---
    private function getMyParent(){
        return $this->getInstanceValue('_Parent',function(){
            if(!array_key_exists('parent',$this->Json)) throw new ex\Error($this,'form [' .$this->Url. '] parent is not defined.');
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
                if(!file_exists($Path)) throw new ex\Error($this,'form [' .$this->Url. '] template is not defined.');
                
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