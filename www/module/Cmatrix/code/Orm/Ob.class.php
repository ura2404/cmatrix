<?php
/**
 * Class Object
 * 
 * Класс для датамоделей сущностей
 *
 * @author ura@itx.ru
 * @version 1.0 2020-08-16
 */

namespace Cmatrix\Orm;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;

class Ob {
    protected $Id;
    protected $Data = [];
    protected $Changed = [];    // массив изменённых свойств
    
    protected $_Props;
    
    // --- --- --- --- --- --- --- ---
    function __construct($id=null){
        $this->Id = $id;
        
        $this->init();
    }
    
    // --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Url' : return $this->getMyUrl();
            case 'Props' : return $this->getMyProps();
            default : 
                $MyName = $name{0} === '_' ? substr($name,1) : $name;
                if(!array_key_exists($MyName,$this->Props)) throw new ex\Error($this,'property ['.$MyName.'] of entity ['.$this->Url.'] is not defined.');
                
                if($name{0} === '_' and array_key_exists($MyName,$this->Changed)) return $this->Changed[$MyName];
                else return $this->Data[$MyName];
        }
    }
    
    // --- --- --- --- --- --- ---
    protected function init(){
        $this->Data = array_map(function($prop){ return null; },$this->Props);
    }
    
    // --- --- --- --- --- --- ---
    protected function getMyUrl(){
        return str_replace(["\\",'/Datamodel'],['/',null],get_class($this));
    }
    
    // --- --- --- --- --- --- ---
    protected function getMyProps(){
        return ide\Datamodel::get($this->Url)->Props;
    }
    
}
?>