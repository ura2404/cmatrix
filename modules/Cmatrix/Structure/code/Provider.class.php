<?php
/**
 * Class \Cmatrix\Structure\Provider
 * 
 * @author ura@itx.ru 
 * @version 1.0 2021-02-01
 */
 
namespace Cmatrix\Structure;
use \Cmatrix\Kernel as kernel;
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
    abstract public function sqlValue($val);
        
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    public function getPropType(array $prop){
        if($prop['type'] === '::id::')      return 'BIGINT';
        elseif($prop['type'] === '::ip::')  return 'VARCHAR(45)'; // 15 - ipv4, 45 - ipv6
        elseif($prop['type'] === '::hid::') return 'VARCHAR(32)';
        elseif($prop['type'] === 'string')  return 'VARCHAR' .(isset($prop['length']) ? '('. $prop['length'] .')' : null);
        else return strtoupper($prop['type']);
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