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
    protected $_Module;
    protected $_Part;
    protected $_Path;
    
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
            case 'Module' : return $this->getMyModule();
            case 'Part'   : return $this->getMyPart();
            case 'Path'   : return $this->getMyPath();
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
    protected function getMyModule(){
        return $this->getInstanceValue('_Module',function(){
            if(count($this->Arr) < 1) return null;
            $Arr = $this->Arr;
            $Module = array_shift($Arr);
            return $Module;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyPart(){
        return $this->getInstanceValue('_Part',function(){
            if(count($this->Arr) < 2) return null;
            $Arr = $this->Arr;
            array_shift($Arr);
            $Part = array_shift($Arr);
            return $Part;
        });
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            if(count($this->Arr) < 2) return null;
            $Arr = $this->Arr;
            array_shift($Arr);
            array_shift($Arr);
            $Path = implode('/',$Arr);
            return $Path;
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