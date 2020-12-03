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

class Form extends kernel\Reflection{
    static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path' : return $this->getMyPath();
            case 'Files' : return $this->getMyFiles();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Form = kernel\Url::get($this->Url)->Path;
            $Path = Part::get($this->Url)->Path .CM_DS. $Form;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error('Form "'. $Form .'" is not exists.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyFiles(){
        $Files = array_diff(scandir($this->Path),['.','..']);
        $Files = array_filter($Files,function($value){
            $Path = $this->Path .'/'. $value;
            return is_dir($Path) && $value{0} !== '_' ? false : true;
        });
        return $Files;
    }    
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>