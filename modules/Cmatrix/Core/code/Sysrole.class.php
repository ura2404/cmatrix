<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-12-09
 */

namespace Cmatrix\Core;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Sysrole extends kernel\Reflection {
    static $INSTANCES = [];
    protected $Key;
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        $this->Key = md5('current session');
        
        parent::__construct($this->Key);
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}
?>