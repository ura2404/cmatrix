<?php
/**
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Exception;

class Error extends \Cmatrix\Kernel\Exception{

    // --- --- --- --- --- --- --- ---
    function __construct($message,$code = 0,\Exception $previous = null){
        parent::__construct('Error // '. $message,$code,$previous);
    }
}

?>