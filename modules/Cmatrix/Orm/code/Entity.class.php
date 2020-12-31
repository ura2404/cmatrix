<?php
/**
 * Class Cmatrix\Orm\Entity
 *
 * @author ura@itx.ru
 * @version 1.0 2020-12-19
 */

namespace Cmatrix\Orm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Entity{
    protected $Dm;
    protected $Data = [];       // массив значений свойств
    protected $Changed = [];    // массив изменённых свойств
    
    // --- --- --- --- --- --- --- ---
    function __construct($id,$dm){
        $this->Dm = $dm;
        
        $this->init();
        
        if($id) $this->getInstance($id);
        else  $this->createInstance();
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Dm' : return $this->Dm;
            case 'Values' : return $this->getValues();
            default : 
                if(!array_key_exists($name,$this->Dm->Props)) throw new ex\Property($this->Dm,$name);
                return$this->Data[$name];
        }
    }
    
    // --- --- --- --- --- --- --- ---
    function __set($name,$value){
        switch($name){
            default : 
                //if(!array_key_exists($name,$this->Dm->Props)) throw new ex\Property($this->Dm,$name);
                $this->setValue($name,$value);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function init(){
        $this->Data = array_map(function($prop){ return null; },$this->Dm->Props);
        $this->Changed = $this->Data;
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * забыть что что-то изменялось
     */
    private function flush(){
    }
    
    // --- --- --- --- --- --- --- ---
    private function createInstance(){
        $this->Dm->beforeCreate($this);
        $this->flush();
    }   

    // --- --- --- --- --- --- --- ---
    private function getInstance(){
        $this->Changed = array_map(function($prop){ return null; },$this->Changed);
    }   

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function setValue($name,$value){
        if(!array_key_exists($name,$this->Dm->Props)) throw new ex\Property($this->Dm,$name);        
        
        $value1 = $this->Data[$name]; $value2 = $value;
        settype($value1, "string"); settype($value2, "string");
        
        if($value1 !== $value2) $this->Changed[$name] = $this->Data[$name];
        $this->Data[$name] = $value;
        
        return $this;
    }   

    // --- --- --- --- --- --- --- ---
    public function setValues(array $values){
        array_map(function($name,$value){
            if($name{0} === '_') return;
            $this->setValue($name,$value);
        },array_keys($values),array_values($values));
        
        return $this;
    }

    // --- --- --- --- --- --- --- ---
    public function getValues(&$values=null){
        if(!$values) return $this->Data;
        
        $values = $this->Data;
        return $this;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function create($dm){
        return new self(null,$dm);
    }
    
    // --- --- --- --- --- --- --- ---
    static function get($id,$dm){
        return new self($id,$dm);
    }
}
?>