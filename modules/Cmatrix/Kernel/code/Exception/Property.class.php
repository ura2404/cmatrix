<?php
/**
 * @author ura@itx.ru
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Kernel\Exception;

class Property{

    // --- --- --- --- --- --- --- ---
    function __construct($ob,$name,$code = 0,\Exception $previous = null){
        throw new \Cmatrix\Kernel\Exception('Class "' .get_class($ob). '" property "' .$name. '" is not defined.',$code,$previous);
    }
}
?>