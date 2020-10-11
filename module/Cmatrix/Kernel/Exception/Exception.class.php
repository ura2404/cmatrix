<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Exception;

class Exception extends \Exception {
    private $Ob;

    // --- --- --- --- --- --- --- ---
    function __construct($ob=null,$message){
        $this->Ob = $ob;
        parent::__construct(($ob ? get_class($this->Ob).' // ' : '') . $message);
    }
    
    // --- --- --- --- --- --- --- ---
    /*
    function __get($name){
        switch($name){
            case 'Message' : return $this->getMessage();
        }
    }
    */
}
?>