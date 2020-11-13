<?php
/**
 * Class Page
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Web\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Page extends kernel\Reflection{
    static $INSTANCES = [];
    protected $Url;
    
    protected $_Path;
    protected $_Json;
    protected $_Form;
    
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
            case 'Form' : return $this->getMyForm();
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
            if(!file_exists($Path) || !file_exists($Path .'/config.json')) throw new ex\Error('page descriptor [' .$this->Url. '] is not found.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getMyJson(){
        return $this->getInstanceValue('_Json',function(){
            return json_decode(file_get_contents($this->Path.'/config.json'),true);
        });
    }
    
    private function getMyForm(){
        return $this->getInstanceValue('_Form',function(){
            if(!isset($this->Json['page']['form'])) throw new ex\Error('page [' .$this->Url. '] form url is not defined.');
            return $this->Json['page']['form'];
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