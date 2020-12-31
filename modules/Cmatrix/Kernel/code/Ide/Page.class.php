<?php
/**
 * Class Page
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Page extends kernel\Reflection{
    //static $INSTANCES = [];
    protected $Url;
    
    protected $_Path;
    protected $_Config;
    protected $_Form;
    
    // --- --- --- --- --- --- --- ---
    function __construct($url){
        $this->Url = $url;
        parent::__construct($url);
        
        //dump(self::$REFINSTANCES);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        //dump($name);
        switch($name){
            case 'Path'   : return $this->getMyPath();
            case 'Config' : return $this->getMyConfig();
            case 'Form'   : return $this->getMyForm();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getFormInstance($url){
        return Form::get($url);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    /**
     * @return string - path to page description folder
     */
    private function getMyPath(){
        return $this->getInstanceValue('_Path',function(){
            $Path = kernel\Ide\Part::get($this->Url)->Path .CM_DS.'page'.CM_DS. kernel\Url::get($this->Url)->Path;
            if(!file_exists($Path) || !file_exists($Path .CM_DS. 'config.json')) throw new ex\Error('page descriptor [' .$this->Url. '] is not found.');
            return $Path;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Config - page description config
     */
    private function getMyConfig(){
        return $this->getInstanceValue('_Config',function(){
            return kernel\Config::get($this->Path);
        });
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * @return \Cmatrix\Kernel\Ide\Form - form instance
     */
    private function getMyForm(){
        return $this->getInstanceValue('_Form',function(){
            if(!($FormUrl = $this->Config->getValue('page/form'))) throw new ex\Error('page [' .$this->Url. '] form url is not defined.');
            return $this->getFormInstance($FormUrl);
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