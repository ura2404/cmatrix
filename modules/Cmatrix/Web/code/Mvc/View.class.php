<?php
/**
 * Class View
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc;
use \Cmatrix\Kernel\Exception as ex;

class View {
    protected $Form;

    // --- --- --- --- --- --- --- ---
    function __construct(\Cmatrix\Web\Ide\Form $form){
        $this->Form = $form;
    }

    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            case 'Data' : return $this->getMyData();
            default : throw new ex\Property($this,$name);
        }
    }

    // --- --- --- --- --- --- --- ---
    protected function getMyData(){
        return;
    }
}

?>