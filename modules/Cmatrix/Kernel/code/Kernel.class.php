<?php
/**
 * @author ura@itx.su
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel;

class Kernel extends \Cmatrix\Kernel\Reflection {
    //static $INSTANCES = [];
    
    // --- --- --- --- --- --- --- ---
    function __construct(){
        parent::__construct('kernel.kernel');
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(){
        return new self();
    }
}
?>