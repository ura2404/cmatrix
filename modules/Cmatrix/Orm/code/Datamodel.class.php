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

class Datamodel extends \Cmatrix\Kernel\Reflection {
    static $C = [];
    
    public $Code;
    public $Name;
    
    protected $Props;
    protected $_Props;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        $this->init();
        parent::__construct();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Props' : return $this->getMyProps();
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function init(){
        $Url = str_replace('\\','/',str_replace('\Dm',null,get_class($this)));
        $Own    = kernel\Ide\Datamodel::get($Url);
        $Parent = kernel\Ide\Datamodel::get($Own->Parent);
        
        dump($Own->Props);
        //dump($Own->OwnProps);
    }
    
    /*protected function getMySapi(){
        return $this->getInstanceValue('_Sapi',function(){
            $Sapi = php_sapi_name();
            if($Sapi=='cli') return 'CLI';
            elseif(substr($Sapi,0,3)=='cgi') return 'CGI';
            elseif(substr($Sapi,0,6)=='apache') return 'APACHE';
        });
    }*/
    
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
        
        /*
        dump($url);
        dump(kernel\Ide\Datamodel::get($url)->Path);
        $ClassName = kernel\Ide\Datamodel::get($url)->Class;
        return new $ClassName();
        */
    }
}
?>