<?php
/**
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Exception;
use \Cmatrix as cm;
use \Cmatrix\Kernel as kernel;

class Error extends kernel\Exception{

    // --- --- --- --- --- --- --- ---
    function __construct($ob,$message){
        $this->Ob = $ob;
        parent::__construct($ob,'Error // '. $message);
    }
}

?>