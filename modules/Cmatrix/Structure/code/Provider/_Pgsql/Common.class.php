<?php
/**
 * Class \Cmatrix\Structure\Provider\Pgsql\Common
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure\Provider\Pgsql;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Common {
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            //case 'Sql' : return $this->getMySql();
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    protected function tableName($name){
        return str_replace('/','_',$name);
    }
    
    // --- --- --- --- --- --- --- ---
    protected function propType($prop){
        return $prop['type'];
    }
}
?>