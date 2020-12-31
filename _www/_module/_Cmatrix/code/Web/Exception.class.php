<?php
/**
 * Class Page
 *
 * @author ura@itx.ru
 * @version 1.0 2020-08-12
 */

namespace Cmatrix\Web;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Ide as ide;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Mvc as mvc;

class Exception extends Page {
    static $MESSAGE;
    static $TRACE = [];
    
    // --- --- --- --- --- --- --- ---
    function __construct($url=''){
        parent::__construct('exception');
    }
    
    // --- --- --- --- --- --- --- ---
    function __set($name,$value){
        switch($name){
            case 'Exception' : 
                self::$MESSAGE = $value->getMessage();
                self::$TRACE = $this->trace($value->getTraceAsString());
                break;
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }
    
    // --- --- --- --- --- --- --- ---
    private function trace($trace){
        kernel\Kernel::get();
        
        if(is_array($trace)){
            return implode('',$trace);
        }
        else{
            $trace = str_replace(kernel\Kernel::$HOME,'',$trace);
        }
        
        return $trace;
    }
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($e=''){
        return new self($e);
    }
    
}
