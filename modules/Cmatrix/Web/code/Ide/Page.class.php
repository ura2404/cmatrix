<?php
/**
 * Class Page
 *
 * @author ura@itx.ru 
 * @version 1.0 2020-07-21
 */

namespace Cmatrix\Web\Ide;
use \Cmatrix\Kernel as kernel;
use \Cmatrix\Kernel\Exception as ex;

class Page extends kernel\Ide\Page{
    static $INSTANCES = [];
    
    // --- --- --- --- --- --- --- ---
    function __get($name){
        switch($name){
            default : return parent::__get($name);
        }
    }
    
    // --- --- --- --- --- --- --- ---
    protected function getFormInstance($url){
        return Form::get($url);
    }

    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    // --- --- --- --- --- --- --- ---
    static function get($url){
        return new self($url);
    }
}
?>