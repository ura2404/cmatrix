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
    
    protected $_Props;
    protected $_PropsNames;

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
            case 'Instance'   : return $this->Instance;
            case 'Props'      : return $this->getMyProps();
            case 'PropsNames' : return $this->getMyPropsNames();
            default : throw new ex\Error('class "' .get_class($this). '" property "' .$name. '" is not defined.');
        }
    }

    // --- --- --- --- --- --- ---
    function __set($name,$value){
        switch($name){
            default : 
                if(in_array('_'.$name,$this->PropsNames)) $Name = '_'.$name;
                else if(in_array($name,$this->PropsNames)) $Name = $name;
                else throw new ex\Error('class "' .get_class($this). '" property "' .$name. '" is not defined.');
                
                static::$INSTANCES[$this->Key]->$Name = $this->$Name = $value;
        }
    }

    // --- --- --- --- --- --- ---
    protected function getMyProps(){
        if($this->_Props) return $this->_Props;
        
        $Reflect = new \ReflectionClass($this);
		$Statics = $Reflect->getStaticProperties();
        
		// приватные свойства не трогать, пусть останутся лично классу
		//$Props = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
		$this->_Props = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
		
        $this->_Props = array_map(function($prop) use($Statics){
            $Name = $prop->getName();
            // статику не переносим
            return array_key_exists($Name,$Statics) ? null : $prop;
		},$this->_Props);
		
		return $this->_Props = array_filter($this->_Props,function($value){ return !!$value; });
    }
    
    // --- --- --- --- --- --- ---
    protected function getMyPropsNames(){
        if($this->_PropsNames) return $this->_PropsNames;
        
		return $this->_PropsNames = array_map(function($prop){
		    return $prop->getName();
		},$this->Props);
    }
    
    // --- --- --- --- --- --- ---
    protected function createInstance(){
        //dump(get_class($this),'createInstance');
        static::$INSTANCES[$this->Key] = $this;
    }

    // --- --- --- --- --- --- ---
    protected function getInstance(){
        array_map(function($name){
            $this->$name = static::$INSTANCES[$this->Key]->$name;
        },$this->PropsNames);
        
        /*
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
		*/
    }
    
    // --- --- --- --- --- --- ---
    protected function getInstanceValue($key,$_fun){
        //dump(static::$INSTANCES[$this->Key]);
        
        if(!$_fun instanceof \Closure) throw new ex\Error('invalid instance "' .$key. '" of function');
        
        if(static::$INSTANCES[$this->Key]->$key) return static::$INSTANCES[$this->Key]->$key;
        return static::$INSTANCES[$this->Key]->$key = $this->$key = $_fun();
    }

}
?>