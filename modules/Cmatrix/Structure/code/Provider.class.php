<?php
/**
 * Class \Cmatrix\Structure\Provider
 * 
 * @author ura@itx.ru 
 * @version 1.0 2021-02-01
 */
 
namespace Cmatrix\Structure;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Orm as orm;
use \Cmatrix\Kernel\Exception as ex;

abstract class Provider implements \Cmatrix\Structure\iProvider {
    static $PROVIDERS = ['pgsql','mysql','sqlite3'];

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : throw new ex\Property($this,$name);
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    abstract public function sqlCreateSequence(iModel $prop);
    abstract public function sqlCreateTable(iModel $model);
    abstract public function getPropNotNull($prop);
    abstract public function sqlCreatePk(iModel $model);
    abstract public function sqlCreateUniques(iModel $model);
    abstract public function sqlCreateGrant(iModel $model);
    abstract public function sqlCreateInit(iModel $model);
    
    // --- --- --- --- --- --- --- ---
    /**
     * Функция sqlValue
     * Для формирования sql представления значения для подстановки в запросы
     * 
     * @return mix - представление значения
     * 
     */
    public function sqlValue($prop,$val,$cond='='){
        if(gettype($val) === 'string' && strStart($val,'raw::')) return strAfter($val,'raw::');
        else switch($prop['type']){
            case 'bool' :
                return orm\Query\Value::get($val)->BoolValue;
                
            case 'timestamp' :
                return orm\Query\Value::get($val)->TsValue;

            case '::id::' :
            case 'integer' :
                return orm\Query\Value::get($val)->IntegerValue;
                
            case 'real' :
                return orm\Query\Value::get($val)->RealValue;
                
            case '::hid::' :
            case '::ip::' :
            case 'string' :
            case 'text' :
            default :
                return "'" .str_replace("'","''",$val). "'";
        }
        
    }
        
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function getPropType(array $prop){
        $Type = $prop['type'];
        
        if($Type === '::id::')       return 'BIGINT';
        elseif($Type === '::ip::')   return 'VARCHAR(45)'; // 15 - ipv4, 45 - ipv6
        elseif($Type === '::hid::')  return 'VARCHAR(32)';
        elseif($Type === '::pass::') return 'VARCHAR(255)';
        elseif($Type === 'string')   return 'VARCHAR' .(isset($prop['length']) ? '('. $prop['length'] .')' : null);
        else return strtoupper($Type);
    }
    
    // --- --- --- --- --- --- --- ---
    /**
     * switch(default) - не подойдёт, так как невозможно будевы типы анализировать
     */
    public function getPropDefault(iModel $model, array $prop){
        if($prop['default'] === null)          return;
        elseif($prop['default'] === true)      return 'TRUE';
        elseif($prop['default'] === false)     return 'FALSE';
        elseif($prop['default'] === '::now::') return 'CURRENT_TIMESTAMP';
        else return "'" .$prop['default']. "'";
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($provider){
        if(!in_array($provider,self::$PROVIDERS)) throw new ex\Error('provider "' .$provider. '" is not valid.');
        
        $ClassName = '\Cmatrix\Structure\Provider\\' .ucfirst($provider);
        return new $ClassName();
    }
}
?>