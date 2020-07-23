<?php
/**
 * Class Twig
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Mvc\Controller;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Twig extends \Cmatrix\Mvc\Controller {
    
    // --- --- --- --- --- --- --- ---
    function __construct($view, $model){
        parent::__construct($view, $model);
        
        cm\Kernel\Vendor::reg('Twig');
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getMyData(){
        
    }
}

?>