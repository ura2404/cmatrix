<?php
/**
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Exception;

class Warning extends Exception{

    // --- --- --- --- --- --- --- ---
    function __construct($ob=null,$message){
        parent::__construct($ob,'Warning // '. $message);
    }
}

?>