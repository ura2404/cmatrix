<?php
/**
 * Class Controller
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-23
 */

namespace Cmatrix\Web\Mvc;
use \Cmatrix\Kernel\Exception as ex;

abstract class Controller {
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
            default : throw new ex\Property($this,$name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    abstract protected function getMyData();
    
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get(\Cmatrix\Web\Ide\Form $form){
        $ClassName = '\Cmatrix\Web\Mvc\Controller\\' . ucfirst($form->Type);
        return new $ClassName(View::get($form), Model::get($form));        
    }
}
?>