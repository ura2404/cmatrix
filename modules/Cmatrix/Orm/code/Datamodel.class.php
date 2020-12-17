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
    static $INSTANCES = [];
    
    protected $_Props;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct(get_class($this));
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
        $ClassName = kernel\Ide\Datamodel::get($url)->Class;
        return new $ClassName();
    }
}
?>