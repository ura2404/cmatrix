<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-12-09
 */

namespace Cmatrix\Core;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Session extends kernel\Reflection {
    static $INSTANCES = [];
    protected $Key;
    
    //protected $_Instance;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        $this->Key = md5('current session');
        
        parent::__construct($this->Key);
    }
    
    // --- --- --- --- --- --- --- ---
	/*function __get($name){
        if($name === 'Instance') return $this->Instance;
        else return $this->Instance->$name;
	}
    */

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
    
    // --- --- --- --- --- --- --- ---
    static function reg(){
        return new self();
    }
}
?>