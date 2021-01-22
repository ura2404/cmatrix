<?php
/**
 * Class Url
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-01
 */
 
namespace Cmatrix\Kernel;
use \Cmatrix\Kernel\Exception as ex;

class Url extends Reflection{
    static $SRC = ['raw','form','res'];
    //static $INSTANCES = [];
    
    private $U;
    
    protected $_Url;
    protected $_Src;
    protected $_Arr;
    protected $_Part1;
    protected $_Part2;
    protected $_Part3;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->U = $url;
        
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            //case 'U'      : return $this->U;
            case 'Url'    : return $this->getMyUrl();
            case 'Src'    : return $this->getMySrc();
            case 'Arr'    : return $this->getMyArr();
            
            case 'Module' : 
            case 'Part1'  : return $this->getMyPart1();
            
            case 'Part'   : 
            case 'Part2'  : return $this->getMyPart2();
            
            case 'Path'   : 
            case 'Part3'  : return $this->getMyPart3();
            
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyUrl(){
        return $this->getInstanceValue('_Url',function(){
            return strpos($this->U,'::') === false ? $this->U : strAfter($this->U,'::');
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - тип источника: raw, form, src
     */
    private function getMySrc(){
        return $this->getInstanceValue('_Src',function(){
            if(strpos($this->U,'::') === false) return null;
            
            $Src = strBefore($this->U,'::');
            if(!in_array($Src,self::$SRC)) throw new ex\Error('url "' .$this->U. '" source "' .$Src. '" is not valid.');
            return $Src;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyArr(){
        return $this->getInstanceValue('_Arr',function(){
            return explode('/',$this->Url);
        });
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyPart1(){
        return $this->getInstanceValue('_Part1',function(){
            if(count($this->Arr) < 1) return null;
            $Arr = $this->Arr;
            $Part1 = array_shift($Arr);
            return $Part1;
        });
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyPart2(){
        return $this->getInstanceValue('_Part2',function(){
            if(count($this->Arr) < 2) return null;
            $Arr = $this->Arr;
            array_shift($Arr);
            $Part2 = array_shift($Arr);
            return $Part2;
        });
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyPart3(){
        return $this->getInstanceValue('_Part3',function(){
            if(count($this->Arr) < 3) return null;
            $Arr = $this->Arr;
            array_shift($Arr);
            array_shift($Arr);
            $Part3 = implode('/',$Arr);
            return $Part3;
        });
    }

    
    // --- --- --- --- --- --- --- ---
    protected function getMyModule(){
        return $this->Part1;
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyPart(){
        return $this->Part2;
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyPath(){
        return $this->Part3;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>