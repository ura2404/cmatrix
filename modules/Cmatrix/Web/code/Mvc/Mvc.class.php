<?php
/**
 * Class Mvc
 * 
 * @author ura@itx.su
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc;
use \Cmatrix\Kernel\Exception as ex;
use \Cmatrix\Web as web;

class Mvc {
    /**
     * Instance of \Cmatrix\Web\Mvc\Controller
    */
    private $Controller;
    
    // --- --- --- --- --- --- --- ---
    function __construct(\Cmatrix\Web\Ide\Form $form){
        $this->Controller = Controller::get($form);
    }
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Html' : return $this->Controller->Data;
        }
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(\Cmatrix\Web\Ide\Form $form){
        return new self($form);
    }
}
?>