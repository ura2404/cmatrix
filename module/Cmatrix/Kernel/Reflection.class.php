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
        if(gettype($key) === 'string') $key = md5($key);
        elseif(is_array($key)) $key = md5(serialize($key));
        
        $this->Key = $key;
        if(isset(static::$INSTANCES[$this->Key])) $this->getInstance();
        else $this->createInstance();
    }

    // --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Instance' : return $this->Instance;
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }

    // --- --- --- --- --- --- ---
    function __set($name,$value){
        switch($name){
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- ---
    protected function createInstance(){
        //dump(get_class($this),'createInstance');
        static::$INSTANCES[$this->Key] = $this;
    }

    // --- --- --- --- --- --- ---
    protected function getInstance(){
        //dump(get_class($this),'getInstance');
		$Reflect = new \ReflectionClass($this);
		
		// приватные свойства не трогать, пусть останутся лично классу
		//$Props = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
		$Props = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
		//dump($Props);
		
		$Statics = $Reflect->getStaticProperties();
		
        array_map(function($prop) use($Statics){
            $Name = $prop->getName();
            if(array_key_exists($Name,$Statics)) return;    // статику не переносим
		    
		    $this->$Name = static::$INSTANCES[$this->Key]->$Name;
		},$Props);
    }
    
    // --- --- --- --- --- --- ---
    protected function getInstanceValue($key,$_fun){
        if(!$_fun instanceof \Closure) throw new ex\Error($this,'invalid instance [' .$key. '] of function');
        
        if(static::$INSTANCES[$this->Key]->$key) return static::$INSTANCES[$this->Key]->$key;
        return static::$INSTANCES[$this->Key]->$key = $this->$key = $_fun();
    }

}
?>