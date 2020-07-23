<?php
/**
 * @author ura@itx.ru
 * @vesion 1.0 2020-07-21
 */

namespace Cmatrix;

class Exception extends \Exception{
    private $Ob;

    // --- --- --- --- --- --- --- ---
    function __construct($ob,$message){
        $this->Ob = $ob;
        parent::__construct('Error ( ' .get_class($this->Ob) .' ) : '. $message);
    }
}

?>