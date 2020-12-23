<?php
/**
 * Class Cmatrix\Orm\Datamodel
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-17
 */

namespace Cmatrix\Orm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

abstract class Datamodel extends \Cmatrix\Kernel\Reflection implements iDatamodel{
    static $C = [];
    
    public $Code;
    public $Name;
    
    protected $_Props;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        $this->init();
        parent::__construct();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Url'   : return $this->getUrl();
            case 'Props' : return $this->getMyProps();
            default : return parent::__get($name);
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function init(){
        $Own    = kernel\Ide\Datamodel::get($this->Url);
        $Parent = kernel\Ide\Datamodel::get($Own->Parent);
        
        //dump($Own->Props);
        //dump($Own->OwnProps);
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyProps(){
        return $this->getInstanceValue('_Props',function(){
            return kernel\Ide\Datamodel::get($this->Url)->Props;
        });
    }
    
    // --- --- --- --- --- --- --- ---
    private function getUrl(){
        return str_replace('\\','/',str_replace('\Dm',null,get_class($this)));
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        if(CM_MODE === 'development'){
            $ClassName = kernel\Ide\Datamodel::get($url)->ClassName;
            $Dm = new $ClassName();
            
            isset(self::$C[$url]) ? null : self::$C[$url] = 0;
            if(!self::$C[$url]++) kernel\Ide\Cache::get('dm')->updateValue($url,serialize($Dm));
            return $Dm;
        }
        else{
            $Sc = kernel\Ide\Cache::get('dm')->getValue($url);
            return unserialize($Sc);
        }
    }
}
?>