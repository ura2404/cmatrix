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
        $_file = function($path){
            return strAfter($path,CM_ROOT);
        };
        
        $Trace = array_map(function($value) use($_file){
            return 
                "\t". (array_key_exists('file',$value) ? $_file($value['file']) : null) .':'. (array_key_exists('line',$value) ? $value['line'] : null)
                .(array_key_exists('class',$value) ? " class:". $value['class'] : null)
                .(array_key_exists('function',$value) ? " function:". $value['function'] : null);
        },$e->getTrace());
        
        $Message = $e->getMessage(). "\nFile: ". $_file($e->getFile()) .':'. $e->getLine() ."\nTrace:\n". implode("\n",$Trace);
        
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