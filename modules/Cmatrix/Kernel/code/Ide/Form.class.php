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
    protected $_Config;
    protected $_Parent;
    protected $_Type;
    
    private $Types = ['html','php','element','twig'];
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Path'      : return $this->getMyPath();
            case 'Config'    : return $this->getMyConfig();
            case 'Parent'    : return $this->getMyParent();
            case 'Type'      : return $this->getMyType();
            //case 'Files' : return $this->getMyFiles();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - path to page description folder
     */
    protected function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Form = kernel\Url::get($this->Url)->Path;
            $Path = Part::get($this->Url)->Path.CM_DS.'form'.CM_DS. $Form;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error('Form "'. $Form .'" is not exists.');
            return $Path;
        });
    }
    // --- --- --- --- --- --- --- ---
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            dump($this->Path .CM_DS. 'config.json','config');
            return kernel\Config::get($this->Path .CM_DS. 'config.json');
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
    private function getMyType(){
        return $this->getInstanceValue('_Type',function(){
            if(($Type = $this->Config->getValue('form/type'))===false) throw new ex\Error('form "' .$this->Url. '" type is not defined.');
            if(!in_array($Type,$this->Types)) throw new ex\Error('form "' .$this->Url. '" type "' .$Type. '"is not valid.');
            return $Type;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return array - list of files in
     */
    /*
    protected function getMyFiles(){
        $Files = array_diff(scandir($this->Path),['.','..']);
        $Files = array_filter($Files,function($value){
            $Path = $this->Path .'/'. $value;
            return is_dir($Path) && $value{0} !== '_' ? false : true;
        });
        return $Files;
    } 
    */
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>