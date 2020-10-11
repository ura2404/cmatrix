<?php
/**
 * Class Page
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Page extends cm\Kernel\Reflection{
    static $INSTANCES = [];
    protected $Url;
    protected $Path;
    
    protected $_Json;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        $this->Path = $this->getMyPath();
        
        parent::__construct($url);
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Form' : return $this->getMyForm();
            case 'Json' : return $this->getMyJson();
            default : return parent::__get($name);
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMyPath(){
        $Path = Module::get($this->Url)->Path .'/page/'. strAfter($this->Url,'/');
        if(!file_exists($Path) || !file_exists($Path .'/page.json')) throw new ex\Error($this,'page descriptor [' .$this->Url. '] is not found.');
        return $Path;
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyJson(){
        return $this->getInstanceValue('_Json',function(){
            return json_decode(file_get_contents($this->Path.'/page.json'),true);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyForm(){
        if(!isset($this->Json['form'])) throw new ex\Error($this,'page [' .$this->Url. '] form url is not defined.');
        return $this->Json['form'];
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>