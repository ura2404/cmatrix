<?php
/**
 * Class Form
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-03
 */
 
namespace Cmatrix\Kernel\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Resource extends kernel\Reflection{
    static $TYPES = ['ico','png','jpg','css','less','js'];
    
    //static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    protected $_Type;
    protected $_Src;

    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'Type' : return $this->getMyType();
            case 'Src'  : return $this->getMySrc();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            switch($this->Src){
                case 'form' : $Path = kernel\Ide\Part::get($this->Url)->Path .CM_DS.'form'.CM_DS. kernel\Url::get($this->Url)->Path;
                              break;
                case 'res'  : 
                case 'raw'  : $Path = kernel\Ide\Part::get($this->Url)->Path .CM_DS.'res'.CM_DS. kernel\Url::get($this->Url)->Path;
                              break;
                default :
            }
            
            if(!file_exists($Path)) throw new ex\Error('resource "' .$this->Url. '" is not exists.');
            
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyType(){
        return $this->getInstanceValue('_Type',function(){
            $Type = strRAfter($this->Url,'.');
            if(!in_array($Type,self::$TYPES)) throw new ex\Error('resource "' .$this->Url. '" type "' .$Type. '"is not valid.');
            return $Type;
        });
    }

    // --- --- --- --- --- --- --- ---
    private function getMySrc(){
        return $this->getInstanceValue('_Src',function(){
             return !($Src = kernel\Url::get($this->Url)->Src) ? 'raw' : $Src;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>