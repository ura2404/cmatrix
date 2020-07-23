<?php
/**
 * Class Controller
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Mvc;
use \Cmatrix as cm;
use \Cmatrix\Kernel\Exception as ex;

class Controller {
    protected $View;
    protected $Model;

    // --- --- --- --- --- --- --- ---
    function __construct($view, $model){
        $this->View = $view;
        $this->Model = $model;
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Data' : return $this->getMyData();
            default : throw new ex\Error($this,'class [' .get_class($this). '] property [' .$name. '] is not defined.');
        }
    }

}

?>