<?php
/**
 * Class \Cmatrix\Structure\Provider\Pgsql\Datamodel
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure\Provider\Pgsql;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Datamodel extends Common{
    protected $Dm;
    
    // --- --- --- --- --- --- --- ---
    function __construct(kernel\Ide\Datamodel $dm){
        $this->Dm = $dm;
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Sql' : return $this->getMySql();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    private function getMySql(){
        $Json = $this->Dm->Json;
        $Sql = [];
        
        $Sql[] = $this->table();
    }
    
    // --- --- --- --- --- --- --- ---
    private function table(){
        $TableName = $this->tableName($this->Dm->Json['code']);
        $ParentTableName = $this->tableName($this->Dm->Json['parent']);
        
        $Arr = [];
        $Arr[] = 'DROP TABLE IF EXISTS ' . $TableName . ' CASCADE;';
        $Arr[] = 'CREATE TABLE ' . $TableName . '(';
        $Arr = array_merge($Arr,$this->props());
        $Arr[] = ')' . ($ParentTableName ? ' INHERITS ('.$ParentTableName.')' : null) .';';
        
        dump($Arr);
        return $Arr;
    }

    // --- --- --- --- --- --- --- ---
    private function props(){
        $Arr = [];
        
        array_map(function($prop) use(&$Arr){
            $Prop = [];
            $Prop[] = $prop['code'];
            $Prop[] = $this->propType($prop);
            $Arr[] = implode(' ',$Prop);
        },$this->Dm->Props);
        
        return $Arr;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(kernel\Ide\Datamodel $dm){
        return new self($dm);
    }
}
?>