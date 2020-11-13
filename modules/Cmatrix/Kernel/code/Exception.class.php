<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-11-13
 */

namespace Cmatrix\Kernel;

class Exception extends \Exception {
    //private $Ob;
    
    // --- --- --- --- --- --- --- ---
    function __construct($message,$code = 0,\Exception $previous = null){
        parent::__construct($message,$code,$previous);
        
        //$this->Ob = $ob;
        //parent::__construct(($ob ? get_class($this->Ob).' // ' : '') . $message);
    }
    
    // --- --- --- --- --- --- --- ---
    static function createMessage($e){
        
        $Trace = array_map(function($value){
            return "\t".$value['file'] .':'. $value['line'] ." class:". $value['class'] ." function:". $value['function'];
        },$e->getTrace());
        
        $Message = $e->getMessage() ."\n". implode("\n",$Trace);
        
        if(PHP_SAPI != 'cli'){
            $Message = explode("\n",$Message);
            $Message = array_map(function($value){
                return '<div>'.str_replace("\t","&nbsp;&nbsp;&nbsp;&bull;",$value).'</div>';
            },$Message);
            $Message= implode('',$Message);
        }
        
        return $Message;
    }
}
?>