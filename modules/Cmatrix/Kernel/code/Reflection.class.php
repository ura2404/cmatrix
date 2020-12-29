<?php
/**
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel;
use \Cmatrix\Kernel\Exception as ex;

class Reflection {
    static $REFINSTANCES = [];
    static $REFPROPS = [];
    static $REFPROPSNAMES = [];
    
    protected $RefKey;
    //protected $OldKey;
    
    //protected $_RefProps;
    //protected $_RefPropsNames;

    // --- --- --- --- --- --- ---
    function __construct($key=null){
        //$this->OldKey = $key;
        if(!$key) $key = '';
        elseif(gettype($key) === 'string') $key = md5($key);
        elseif(is_array($key)) $key = md5(serialize($key));
        else throw new ex\Error('Bad key for create Reflecton instance');
        
        $this->RefKey = get_class($this).'_'.$key;
        if(isset(self::$REFINSTANCES[$this->RefKey])) $this->getInstance();
        else $this->createInstance();
    }

    // --- --- --- --- --- --- ---
    function __get($name){
        //dump($name);
        //dump($this);
        //dump($this->Oldkey);
        switch($name){
            //case 'Instance'      : return $this->Instance;
            case 'RefProps'      : return $this->getMyRefProps();
            case 'RefPropsNames' : return $this->getMyRefPropsNames();
            //default : throw new ex\Error('class "' .get_class($this). '" property "' .$name. '" is not defined.');
            default : throw new ex\Property($this,$name);
        }
    }

    // --- --- --- --- --- --- ---
    function __set($name,$value){
        switch($name){
            default : 
                if(in_array('_'.$name,$this->RefPropsNames)) $Name = '_'.$name;
                else if(in_array($name,$this->RefPropsNames)) $Name = $name;
                else throw new ex\Property($this,$name);
                
                self::$REFINSTANCES[$this->RefKey]->$Name = $this->$Name = $value;
        }
    }

    // --- --- --- --- --- --- ---
    protected function getMyRefProps2(){
        if($this->_RefProps) return $this->_RefProps;
        
        $Reflect = new \ReflectionClass($this);
		$Statics = $Reflect->getStaticProperties();
        
		// приватные свойства не трогать, пусть останутся лично классу
		//$Props = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
		$this->_RefProps = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED);
		
        $this->_RefProps = array_map(function($prop) use($Statics){
            $Name = $prop->getName();
            // статику не переносим
            return array_key_exists($Name,$Statics) ? null : $prop;
		},$this->_RefProps);
		
		return $this->_RefProps = array_filter($this->_RefProps,function($value){ return !!$value; });
    }

    // --- --- --- --- --- --- ---
    protected function getMyRefProps(){
        if(isset(self::$REFPROPS[$this->RefKey])) return self::$REFPROPS[$this->RefKey];
        
        $Reflect = new \ReflectionClass($this);
		$Statics = $Reflect->getStaticProperties();
        
		// приватные свойства не трогать, пусть останутся лично классу
		self::$REFPROPS[$this->RefKey] = $Reflect->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED /*| \ReflectionProperty::IS_PRIVATE*/);
		
        // статику не переносим
        self::$REFPROPS[$this->RefKey] = array_map(function($prop) use($Statics){
            $Name = $prop->getName();
            return array_key_exists($Name,$Statics) ? null : $prop;
		},self::$REFPROPS[$this->RefKey]);
		
		//dump(get_class($this));
		//dump($this->Url,'URL');
		//dump($this->RefKey,'KEY');
		//dump(self::$REFPROPS[$this->RefKey]);
		
		// удалить пустые у вернуть
		return self::$REFPROPS[$this->RefKey] = array_filter(self::$REFPROPS[$this->RefKey],function($value){ return !!$value; });
    }
    
    // --- --- --- --- --- --- ---
    protected function getMyRefPropsNames2(){
        if($this->_RefPropsNames) return $this->_RefPropsNames;
        
		return $this->_RefPropsNames = array_map(function($prop){
		    return $prop->getName();
		},$this->RefProps);
    }

    // --- --- --- --- --- --- ---
    protected function getMyRefPropsNames(){
        if(isset(self::$REFPROPSNAMES[$this->RefKey])) return self::$REFPROPSNAMES[$this->RefKey];
        
		return self::$REFPROPSNAMES[$this->RefKey] = array_map(function($prop){
		    return $prop->getName();
		},$this->RefProps);
    }
    
    // --- --- --- --- --- --- ---
    protected function createInstance(){
        //dump(get_class($this),'createInstance');
        self::$REFINSTANCES[$this->RefKey] = $this;
    }

    // --- --- --- --- --- --- ---
    protected function getInstance(){
        //dump(self::$REFINSTANCES[$this->RefKey],1111);
        //dump(get_class($this),11111);
        //dump($this,2222);
        //dump($this->RefKey,3333);
        //dump($this->OldKey,4444);
        //dump($this->RefPropsNames);
        
        array_map(function($name){
            //dump($name,111);
            //dump($this->OldKey,222);
            //dump(self::$REFINSTANCES[$this->RefKey],333);
            //dump(self::$REFINSTANCES[$this->RefKey]->$name,444);
            $this->$name = self::$REFINSTANCES[$this->RefKey]->$name;
        },$this->RefPropsNames);
    }
    
    // --- --- --- --- --- --- ---
    protected function getInstanceValue($name,$_fun){
        if(!($_fun instanceof \Closure)) throw new ex\Error('invalid function for instance "' .$name. '"');
        
        if(self::$REFINSTANCES[$this->RefKey]->$name !== null) return self::$REFINSTANCES[$this->RefKey]->$name;
        return self::$REFINSTANCES[$this->RefKey]->$name = $this->$name = $_fun();
    }

}
?>