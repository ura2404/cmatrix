<?php
/**
 * Class Resource
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Resource extends cm\Kernel\Reflection{
    static $INSTANCES = [];
    protected $Url;
    
    protected $_Path;
    protected $_Wpath;
    protected $_Kind;
    
    static $KINDS = ['css'];
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        kernel\Kernel::get();
        
        $this->Url = $url;
        
        parent::__construct($url);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'Wpath' : return $this->getMyWpath();
            case 'Kind' : return $this->getMyKind();
            case 'Link' : return  $this->getMyLink();
            default : return parent::__get($name);
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = Module::get($this->Url)->Path .'/res/'. strAfter($this->Url,'/');
            if(!file_exists($Path)) throw new ex\Error($this,'resource [' .$this->Url. '] is not found.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyWpath(){
        return $this->getInstanceValue('_Wpath',function(){
            $Path = Module::get($this->Url)->Wpath .'/res/'. strAfter($this->Url,'/');
            return $Path;
        });
    }

    // --- --- --- --- --- --- --- ---
    private function getMyKind(){
        return $this->getInstanceValue('_Kind',function(){
            $Kind = strRafter($this->Url,'.');
            if(!in_array($Kind,self::$KINDS)) throw new ex\Error($this,'resource [' .$this->Url. '] kind [' .$Kind. '] is not valid.');
            return $Kind; 
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyLink(){
        $Class = '\\Cmatrix\\Kernel\\Ide\\Resource\\' .strFupper($this->Kind);
        return (new $Class($this->Url))->Link;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>