<?php
/**
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel;

class Exception extends \Exception{
    private $Ob;

    // --- --- --- --- --- --- --- ---
    function __construct($ob,$message){
        $this->Ob = $ob;
        parent::__construct(get_class($this->Ob) .' // '. $message);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Message' : return $this->getMessage();
        }
    }
}
?>