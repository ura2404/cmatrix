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
    static $TYPES = ['html','php','element','twig'];
    
    //static $INSTANCES = [];
    
    protected $Url;
    
    protected $_Path;
    protected $_CacheKey;
    protected $_Config;
    protected $_Parent;
    protected $_Type;
    protected $_Content;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Url'       : return $this->Url;
            case 'Path'      : return $this->getMyPath();
            case 'CacheKey'  : return $this->getMyCacheKey();
            case 'Config'    : return $this->getMyConfig();
            case 'Parent'    : return $this->getMyParent();
            case 'Type'      : return $this->getMyType();
            case 'Content'   : return $this->getMyContent();
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
            $Form = kernel\Url::get($this->Url)->Part3;
            $Path = Part::get($this->Url)->Path.CM_DS.'form'.CM_DS. $Form;
            if(!file_exists($Path) || !is_dir($Path)) throw new ex\Error('Form "'. $Form .'" is not exists.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - cache name of form instance in the cache
     */
    private function getMyCacheKey(){
        return $this->getInstanceValue('_CacheKey',function(){
            return md5($this->Url.'/form') .'.'. $this->Type;
        });
    }

    // --- --- --- --- --- --- --- ---
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            return kernel\Config::get($this->Path .CM_DS. 'config.json');
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Form - parent form 
     */
    private function getMyParent(){
        return $this->getInstanceValue('_Parent',function(){
            if(($ParentUrl = $this->Config->getValue('form/parent'))===false) throw new ex\Error('form "' .$this->Url. '" parent is not defined.');
            return $ParentUrl ? static::get($ParentUrl) : null;    // static::get() - НЕ self::get()
        }); 
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - form type
     */
    private function getMyType(){
        return $this->getInstanceValue('_Type',function(){
            if(($Type = $this->Config->getValue('form/type'))===false) throw new ex\Error('form "' .$this->Url. '" type is not defined.');
            if(!in_array($Type,self::$TYPES)) throw new ex\Error('form "' .$this->Url. '" type "' .$Type. '"is not valid.');
            return $Type;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - content of form template file
     */
    private function getMyContent(){
        return $this->getInstanceValue('_Content',function(){
            //$Path = $this->Path.'/form.'.$this->Type;
            $Path = kernel\Ide\Form::get($this->Url)->Path.'/form.'.$this->Type;
            if(!file_exists($Path)) throw new ex\Error('form [' .$this->Url. '] template is not found.');
            return file_get_contents($Path);
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