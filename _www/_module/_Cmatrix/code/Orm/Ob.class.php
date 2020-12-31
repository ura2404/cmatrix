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

// --- --- --- --- --- --- --- ---
class Ob {
    protected $Dm;
    protected $Id;
    protected $Data = [];
    protected $Changed = [];    // массив изменённых свойств
    
    // --- --- --- --- --- --- --- ---
    function __construct(ide\Datamodel $dm,$id=null){
        $this->Dm = $dm;
        $this->Id = $id;
        
        $this->init();
        if($id) $this->_get();
        else $this->_create();
    }
    
    // --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Values' : return $this->Data;
            default : 
                $MyName = $name{0} === '_' ? substr($name,1) : $name;
                if(!array_key_exists($MyName,$this->Data)) throw new ex\Error($this,'property ['.$MyName.'] of entity ['.$this->Dm->Url.'] is not defined.');
                
                if($name{0} === '_' and array_key_exists($MyName,$this->Changed)) return $this->Changed[$MyName];
                else return $this->Data[$MyName];
        }
    }
    
    // --- --- --- --- --- --- ---
    function __set($name,$value){
        switch($name){
            default : 
                if(!array_key_exists($name,$this->Data)) throw new ex\Error($this,'property ['.$name.'] of entity ['.$this->Dm->Url.'] is not defined.');
                $this->Data[$name] = $value;
                
        }
    }
    
    // --- --- --- --- --- --- ---
    protected function init(){
        $this->Data = array_map(function($prop){ return null; },$this->Dm->Props);
    }

    // --- --- --- --- --- --- ---
    protected function _get(){
    }

    // --- --- --- --- --- --- ---
    protected function _create(){
        if($this->Dm->beforeCreate($this) === false) throw new ex\Warning($this,'Невозможно создать экземпляр сущности ' .$this->Dm->Url. '.');
        if($this->Dm->afterCreate($this) === false) throw new ex\Warning($this,'Невозможно создать экземпляр сущности ' .$this->Dm->Url. '.');
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function setValues(array $values){
        array_map(function($key,$value){
            $this->$key = $value;
        },array_keys($values),array_values($values));
    }
    
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url,$id=null){
        return new self($url,$id);
    }
}




class __Ob {
    protected $Dm;
    protected $Id;
    protected $Data = [];
    protected $Changed = [];    // массив изменённых свойств
    
    protected $_Props;
    
    // --- --- --- --- --- --- --- ---
    function __construct(ide\Datamodel $dm,$id=null){
        $this->Dm = $dm;
        $this->Id = $id;
        
        //$this->init();
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