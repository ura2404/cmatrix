<?php
/**
 * Class Form
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Form extends cm\Kernel\Reflection{
    static $INSTANCES = [];
    protected $Url;
    protected $Path;
    
    protected $_Json;
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        cm\Kernel::get();
        
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
        if(!isset($this->Json['type'])) throw new ex\Error($this,'form [' .$this->Url. '] type is not defined.');
        return $this->Json['type'];
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}

?>    
