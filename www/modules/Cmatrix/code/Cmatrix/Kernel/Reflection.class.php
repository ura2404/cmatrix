<?php
/**
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel;
use \Cmatrix\Kernel\Exception as ex;

class Reflection {
    //static $INSTANCES = [];
    protected $Key;

    // --- --- --- --- --- --- ---
    function __construct($key){
        if(isset(self::$INSTANCES[$key])) $this->getInstance($key);
        else $this->createInstance($key);
    }

    // --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Instance' : return $this->Instance;
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }

    // --- --- --- --- --- --- ---
    protected function createInstance($key){
        $this->Key = $key;
        
        $Reflect = new \ReflectionClass($this);
        $Props = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
        $Statics = $Reflect->getStaticProperties();
        
        array_map(function($prop) use($Statics){
            $Name = $prop->getName();
            if(array_key_exists($Name,$Statics)) return;    // статику не переносим
        
            static::$INSTANCES[$this->Key][$Name] = $this->$Name;
        },$Props);
    }

    // --- --- --- --- --- --- ---
    protected function getInstance($key){
		$Reflect = new \ReflectionClass($this);
		
		// приватные свойства не трогать, пусть останутся лично классу
		//$Props = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
		$Props = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
		//dump($Props);
		
		$Statics = $Reflect->getStaticProperties();
		
        array_map(function($prop) use($key,$Statics){
            $Name = $prop->getName();
            if(array_key_exists($Name,$Statics)) return;    // статику не переносим
		    
		    $this->$Name = static::$INSTANCES[$key]->$Name;
		},$Props);
    }
    
    // --- --- --- --- --- --- ---
    protected function updateInstance($key,$value){
        return static::$INSTANCES[$this->Key]->$key = $this->$key = $value;
    }

}
?>