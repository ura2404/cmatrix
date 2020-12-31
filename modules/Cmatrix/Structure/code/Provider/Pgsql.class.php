<?php
/**
 * Class Cmatrix\Structure\Provider\Pgsql
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-12-29
 */
 
namespace Cmatrix\Structure\Provider;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Pgsql {
    
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
}
?>